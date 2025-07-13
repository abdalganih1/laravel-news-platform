<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Remove Post model if not used elsewhere here
use App\Models\Favorite;
use App\Models\User;
use App\Models\Post; // Keep this to get post IDs

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('Seeding dummy data for favorites...');

        // --- REMOVE THE POST FACTORY CALL ---
        // Post::factory(25)->create(); // <-- احذف أو علّق على هذا السطر
        // $this->command->info('Dummy posts seeding is now handled by PostSeeder.');
        // ---

        // للحصول على معرفات المستخدمين والمنشورات الموجودة لإنشاء المفضلة
        $userIds = User::pluck('user_id')->toArray();
        $postIds = Post::pluck('post_id')->toArray();

        // إنشاء مفضلات وهمية
        if (count($userIds) > 0 && count($postIds) > 0) {
            $favorites = [];
            // إنشاء عدد أقل من المفضلة ليتناسب مع عدد المنشورات القليل
            $limit = min(20, count($userIds) * count($postIds));
            for ($i = 0; $i < $limit; $i++) {
                $userId = fake()->randomElement($userIds);
                $postId = fake()->randomElement($postIds);
                $key = $userId . '-' . $postId;

                if (!isset($favorites[$key])) {
                    Favorite::factory()->create([
                        'user_id' => $userId,
                        'post_id' => $postId,
                    ]);
                    $favorites[$key] = true;
                }
            }
             $this->command->info('Dummy favorites seeded (' . count($favorites) . ' unique).');
        } else {
            $this->command->error('Cannot seed favorites. No users or posts found.');
        }

        $this->command->warn('Dummy data seeding finished.');
    }
}