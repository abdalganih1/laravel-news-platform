<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Governorate;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultGovernorate = Governorate::where('name', 'دمشق')->first();
        $aleppoGovernorate = Governorate::where('name', 'حلب')->first();

        // إنشاء مدير النظام
        $adminFirstName = 'مدير';
        $adminLastName = 'النظام';
        User::create([
            'name' => $adminFirstName . ' ' . $adminLastName, // *** إضافة هذا السطر ***
            'first_name' => $adminFirstName,
            'last_name' => $adminLastName,
            'email' => 'admin@news.sy',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'user_role' => 'admin',
            'phone_number' => '0911111111',
            'governorate_id' => $defaultGovernorate ? $defaultGovernorate->governorate_id : null,
            'date_of_birth' => '1980-01-01',
            'notes' => 'حساب مدير النظام الرئيسي'
        ]);

        // إنشاء محرر محتوى
        $editorFirstName = 'محرر';
        $editorLastName = 'محتوى';
        User::create([
            'name' => $editorFirstName . ' ' . $editorLastName, // *** إضافة هذا السطر ***
            'first_name' => $editorFirstName,
            'last_name' => $editorLastName,
            'email' => 'editor@news.sy',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'user_role' => 'editor',
            'phone_number' => '0922222222',
            'governorate_id' => $aleppoGovernorate ? $aleppoGovernorate->governorate_id : null,
            'date_of_birth' => '1985-05-10',
        ]);

        // إنشاء مستخدم عادي
        $userFirstName = 'مستخدم';
        $userLastName = 'عادي';
        User::create([
            'name' => $userFirstName . ' ' . $userLastName, // *** إضافة هذا السطر ***
            'first_name' => $userFirstName,
            'last_name' => $userLastName,
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'user_role' => 'normal',
            'phone_number' => '0933333333',
            'governorate_id' => $defaultGovernorate ? $defaultGovernorate->governorate_id : null,
        ]);

        $this->command->info('Admin, Editor, and Normal users seeded successfully!');
    }
}