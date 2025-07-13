<?php

namespace Database\Factories;

use App\Models\User; // Import User model
use App\Models\Post; // Import Post model (for optional resolution post)
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Claim>
 */
class ClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define some sample Syrian news claims
        $sampleClaims = [
            [
                'title' => 'إشاعة حول توزيع مساعدات في حي المزة',
                'reported_text' => 'وردنا خبر عن قيام منظمة بتوزيع سلات غذائية في حديقة المزة غداً صباحاً. الرجاء التأكد من صحة الخبر.',
                'external_url' => 'https://facebook.com/somepage/posts/12345',
            ],
            [
                'title' => 'هل تم تأجيل امتحانات جامعة دمشق؟',
                'reported_text' => 'يتداول الطلاب على مجموعات واتساب قراراً بتأجيل امتحانات كلية الهندسة المعلوماتية. هل هذا صحيح؟',
                'external_url' => null, // No external link
            ],
            [
                'title' => 'صورة مفبركة لطرقات في حلب',
                'reported_text' => 'هذه الصورة التي تظهر أعمال حفريات ضخمة في حي الحمدانية قديمة وتعود لعام 2018 وليست حديثة كما يدعي المنشور.',
                'external_url' => 'https://twitter.com/someuser/status/12345',
            ],
            [
                'title' => 'خبر ارتفاع سعر أسطوانة الغاز',
                'reported_text' => 'خبر عاجل: ارتفاع جديد في سعر أسطوانة الغاز ليصل إلى 150 ألف ليرة سورية.',
                'external_url' => 'https://some-news-site.com/article/gas-price',
            ],
        ];

        $randomClaim = $this->faker->randomElement($sampleClaims);

        return [
            // Select a random user (assuming you have a UserFactory and seeder)
            'user_id' => User::inRandomOrder()->first()->user_id ?? User::factory(),
            'title' => $randomClaim['title'],
            'external_url' => $randomClaim['external_url'],
            'reported_text' => $randomClaim['reported_text'],
            'user_notes' => 'الرجاء التحقق من هذا الادعاء.',
            'claim_status' => $this->faker->randomElement(['pending', 'reviewed', 'cancelled']),
            // For reviewed claims, add review details
            'admin_notes' => function (array $attributes) {
                return $attributes['claim_status'] === 'reviewed' ? 'تمت المراجعة والرد بمنشور رسمي.' : null;
            },
            'reviewed_by_user_id' => function (array $attributes) {
                // Assign a reviewer (editor/admin) only if the claim is reviewed
                return $attributes['claim_status'] === 'reviewed' ? User::whereIn('user_role', ['editor', 'admin'])->inRandomOrder()->first()->user_id : null;
            },
            'reviewed_at' => function (array $attributes) {
                return $attributes['claim_status'] === 'reviewed' ? now() : null;
            },
            // Optionally link to a resolution post if reviewed
            'resolution_post_id' => function (array $attributes) {
                 return $attributes['claim_status'] === 'reviewed' ? (Post::where('post_status', 'real')->inRandomOrder()->first()->post_id ?? null) : null;
            }
        ];
    }
}