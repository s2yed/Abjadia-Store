<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingCompany;

class ShippingCompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            [
                'name' => ['en' => 'Aramex', 'ar' => 'أرامكس'],
                'logo' => 'banners/hero_placeholder.jpg',
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'SMSA Express', 'ar' => 'سمسا إكسبريس'],
                'logo' => 'banners/hero_placeholder.jpg',
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'DHL', 'ar' => 'دي إتش إل'],
                'logo' => 'banners/hero_placeholder.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($companies as $company) {
            ShippingCompany::create($company);
        }
    }
}
