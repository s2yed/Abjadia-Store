<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => ['en' => 'Hero Image', 'ar' => 'صورة العرض الرئيسية'],
                'description' => ['en' => 'Main hero image for the homepage', 'ar' => 'صورة العرض الرئيسية لمتجر أبجدية'],
                'image_path' => '/images/banners/hero_placeholder.jpg',
                'link' => '#',
                'position' => 'hero_image',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => ['en' => 'Back to School', 'ar' => 'العودة للمدارس'],
                'description' => ['en' => 'Back to school special offer', 'ar' => 'عروض خاصة بمناسبة العودة للمدارس'],
                'image_path' => '/images/banners/back_to_school.png',
                'link' => '#',
                'position' => 'separator_1',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => ['en' => 'Reading Festival', 'ar' => 'مهرجان القراءة'],
                'description' => ['en' => 'Annual reading festival', 'ar' => 'مهرجان القراءة السنوي'],
                'image_path' => '/images/banners/reading_festival.png',
                'link' => '#',
                'position' => 'separator_2',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => ['en' => 'Art & Creativity', 'ar' => 'الفن والإبداع'],
                'description' => ['en' => 'Explore your creativity', 'ar' => 'استكشف مواهبك وإبداعاتك'],
                'image_path' => '/images/banners/art_creativity.png',
                'link' => '#',
                'position' => 'separator_3',
                'is_active' => true,
                'order' => 1,
            ],
        ];

        foreach ($banners as $banner) {
            \App\Models\Banner::create($banner);
        }
    }
}
