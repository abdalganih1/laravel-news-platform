<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Governorate; // تأكد من استيراد النماذج
use App\Models\Region;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء محافظات
        $damascus = Governorate::create(['name' => 'دمشق']);
        $aleppo = Governorate::create(['name' => 'حلب']);
        $homs = Governorate::create(['name' => 'حمص']);
        $latakia = Governorate::create(['name' => 'اللاذقية']);
        $hama = Governorate::create(['name' => 'حماه']);
        // أضف باقي المحافظات حسب الحاجة...

        // إنشاء مناطق وربطها بالمحافظات
        Region::create([
            'governorate_id' => $damascus->governorate_id,
            'name' => 'المزة',
            'gps_coordinates' => '33.501,36.256' // مثال
        ]);
        Region::create([
            'governorate_id' => $damascus->governorate_id,
            'name' => 'كفرسوسة'
        ]);
        Region::create([
            'governorate_id' => $aleppo->governorate_id,
            'name' => 'الحمدانية'
        ]);
        Region::create([
            'governorate_id' => $aleppo->governorate_id,
            'name' => 'الجميلية'
        ]);
        Region::create([
            'governorate_id' => $homs->governorate_id,
            'name' => 'الوعر'
        ]);
        Region::create([
            'governorate_id' => $latakia->governorate_id,
            'name' => 'مشروع الصليبة'
        ]);
        Region::create([
            'governorate_id' => $hama->governorate_id,
            'name' => 'حي البعث'
        ]);
        // أضف باقي المناطق...

        $this->command->info('Governorates and Regions seeded successfully!');
    }
}