<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteInfo;

class SiteInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء سجل واحد لمعلومات الموقع (أو تحديثه إذا كان موجودًا)
        SiteInfo::updateOrCreate(
            ['info_id' => 1], // البحث عن السجل الذي له info_id = 1
            [ // البيانات التي سيتم إنشاؤها أو تحديثها
                'title' => 'حول المنصة الرسمية للأخبار',
                'content' => 'تهدف هذه المنصة لتكون المصدر الرسمي والموثوق للأخبار والمعلومات في سوريا ما بعد التحرير، ومكافحة انتشار الأخبار المزيفة والمضللة. يتم إدارة المحتوى والتحقق منه بواسطة جهات رسمية مختصة لضمان الدقة والمصداقية.',
                'contact_phone' => '+963 XX XXX XXXX',
                'contact_email' => 'contact@news.sy', // بريد افتراضي
                'website_url' => config('app.url'), // يأخذ الرابط من ملف .env
            ]
        );

        $this->command->info('Site information seeded successfully!');
    }
}