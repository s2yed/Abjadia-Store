<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publisher;

class PublisherSeeder extends Seeder
{
    public function run()
    {
        $publishers = [
            [
                'name' => ['en' => 'Dar Al-Shorouk', 'ar' => 'دار الشروق'],
                'bio' => ['en' => 'Leading Egyptian publishing house.', 'ar' => 'دار نشر مصرية رائدة.'],
                'image' => 'banners/hero_placeholder.jpg',
            ],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }
    }
}
