<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; // لاستخدام معرفات المستخدمين الموجودين
use App\Models\Region; // لاستخدام معرفات المناطق الموجودة
use App\Models\Post; // لربط المنشورات ببعضها (تصحيح)

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // جلب معرفات المستخدمين والمناطق الموجودة بشكل عشوائي
        $userIds = User::pluck('user_id')->toArray(); // تأكد من أن المفتاح هو 'id' أو 'user_id'
        $regionIds = Region::pluck('region_id')->toArray();
        $postIds = Post::pluck('post_id')->toArray(); // للحقل corrected_post_id

        return [
            'user_id' => fake()->randomElement($userIds), // اختر ناشرًا عشوائيًا
            'region_id' => fake()->optional()->randomElement($regionIds), // منطقة اختيارية
            'title' => fake()->sentence(6), // عنوان من 6 كلمات
            'text_content' => fake()->paragraphs(3, true), // 3 فقرات نصية
            'post_status' => fake()->randomElement(['pending_verification', 'fake', 'real']),
            'corrected_post_id' => fake()->optional(0.1)->randomElement($postIds), // احتمال 10% لوجود منشور تصحيح
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'), // تاريخ إنشاء عشوائي خلال السنة الماضية
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}