<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Region;
use Illuminate\Support\Facades\Schema; // <--- استيراد Schema

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- الحل: تعطيل القيود، التفريغ، ثم إعادة التفعيل ---
        Schema::disableForeignKeyConstraints(); // تعطيل القيود
        Post::truncate(); // تفريغ الجدول
        Schema::enableForeignKeyConstraints(); // إعادة تفعيل القيود
        // -----------------------------------------------------

        // جلب معرفات المحررين/المدراء لنشر الأخبار
        $editorIds = User::whereIn('user_role', ['editor', 'admin'])->pluck('user_id')->toArray();
        if (empty($editorIds)) {
            $this->command->error('No editors or admins found. Please seed users first.');
            return;
        }

        // جلب معرفات المناطق
        // استخدام first() لتجنب الأخطاء إذا كانت المنطقة غير موجودة
        $damascusRegionId = Region::where('name', 'المزة')->first()?->region_id;
        $aleppoRegionId = Region::where('name', 'الحمدانية')->first()?->region_id;
        $homsRegionId = Region::where('name', 'الوعر')->first()?->region_id;
        $latakiaRegionId = Region::where('name', 'مشروع الصليبة')->first()?->region_id;

        // --- المنشور الأول: خبر حقيقي ومستقل ---
        Post::create([
            'user_id' => fake()->randomElement($editorIds),
            'region_id' => $damascusRegionId,
            'title' => 'افتتاح جسر المشاة الجديد في منطقة المزة',
            'text_content' => 'أعلنت محافظة دمشق عن افتتاح جسر المشاة الجديد في منطقة المزة أوتوستراد، بهدف تسهيل حركة المواطنين وتقليل الحوادث. يأتي هذا المشروع ضمن خطة المحافظة لتحسين البنية التحتية وتأمين سلامة المشاة في المناطق المزدحمة.',
            'post_status' => 'real',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        // --- المنشور الثاني: خبر حقيقي ومستقل آخر ---
        Post::create([
            'user_id' => fake()->randomElement($editorIds),
            'region_id' => $aleppoRegionId,
            'title' => 'حملة تشجير واسعة في حي الحمدانية بحلب',
            'text_content' => 'نظمت مديرية الزراعة بالتعاون مع المجتمع المحلي حملة تشجير واسعة في حي الحمدانية، شملت زراعة أكثر من 500 شجرة متنوعة في الحدائق العامة وعلى أرصفة الشوارع الرئيسية. تهدف الحملة إلى زيادة المساحات الخضراء وتحسين المظهر الجمالي للمدينة.',
            'post_status' => 'real',
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ]);

        // --- المنشور الثالث والرابع: خبر مزيف وخبر حقيقي يصححه ---

        // 3. أولاً، ننشئ الخبر الحقيقي (التصحيح)
        $realPost = Post::create([
            'user_id' => fake()->randomElement($editorIds),
            'region_id' => $homsRegionId,
            'title' => 'مصادر في مديرية التربية تنفي تأجيل امتحانات الشهادة الثانوية',
            'text_content' => 'نفت مصادر رسمية في مديرية التربية بحمص كل الإشاعات المتداولة حول تأجيل امتحانات الشهادة الثانوية. وأكدت المصادر أن الامتحانات ستجرى في مواعيدها المحددة وفقًا للبرنامج الصادر عن وزارة التربية، ودعت الطلاب إلى عدم الانجرار وراء الأخبار غير الموثوقة واستقاء المعلومات من المصادر الرسمية فقط.',
            'post_status' => 'real',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        // 4. ثانياً، ننشئ الخبر المزيف ونربطه بالخبر الحقيقي
        Post::create([
            'user_id' => fake()->randomElement($editorIds),
            'region_id' => $homsRegionId,
            'title' => '[خبر مزيف] قرار عاجل بتأجيل امتحانات الثانوية العامة في حمص',
            'text_content' => 'تداولت صفحات على مواقع التواصل الاجتماعي خبراً يزعم صدور قرار عاجل بتأجيل امتحانات الشهادة الثانوية في محافظة حمص لمدة أسبوع بسبب الظروف اللوجستية. الخبر لا أساس له من الصحة.',
            'post_status' => 'fake',
            'corrected_post_id' => $realPost->post_id, // <-- الربط هنا
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(2),
        ]);

        // --- المنشور الخامس: خبر قيد التحقق ---
        Post::create([
            'user_id' => fake()->randomElement($editorIds),
            'region_id' => $latakiaRegionId,
            'title' => 'أنباء عن انقطاع مياه الشرب في مشروع الصليبة لعدة أيام',
            'text_content' => 'يتناقل سكان مشروع الصليبة في اللاذقية أنباء عن انقطاع مبرمج لمياه الشرب سيستمر لثلاثة أيام لإجراء أعمال صيانة على الشبكة الرئيسية. فريقنا يعمل حاليًا على التحقق من صحة هذه الأنباء من مؤسسة المياه.',
            'post_status' => 'pending_verification',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $this->command->info('Seeded 5 realistic posts.');
    }
}