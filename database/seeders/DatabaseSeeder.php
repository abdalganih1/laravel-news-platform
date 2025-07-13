<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');

        // 1. تشغيل الـ Seeders الأساسية بالترتيب الصحيح
        $this->call([
            LocationSeeder::class,
            UserSeeder::class,
            PostSeeder::class, // <-- أضف هذا السطر هنا
            SiteInfoSeeder::class,
            ClaimSeeder::class,
        ]);

        // 2. (اختياري) تشغيل Seeder للبيانات الوهمية الأخرى (مثل المفضلة)
        // لاحظ أننا أزلنا إنشاء المنشورات من DummyDataSeeder
        if (app()->environment('local', 'development')) {
             $this->command->warn('Running Dummy Data Seeder (for favorites, etc.)...');
             $this->call(DummyDataSeeder::class);
        }

        $this->command->info('Database seeding completed successfully!');
    }
}