<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['en' => 'Apple', 'ar' => 'آبل'],
            ['en' => 'Samsung', 'ar' => 'سامسونج'],
            ['en' => 'Sony', 'ar' => 'سوني'],
            ['en' => 'Faber-Castell', 'ar' => 'فابر كاستل'],
            ['en' => 'Parker', 'ar' => 'باركر'],
            ['en' => 'Moleskine', 'ar' => 'مولسكين'],
        ];

        foreach ($brands as $brandName) {
            Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName['en']),
                'logo' => 'banners/hero_placeholder.jpg', // Placeholder
            ]);
        }
    }
}
