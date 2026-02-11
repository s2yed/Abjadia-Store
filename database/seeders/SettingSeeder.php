<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => [
                    'en' => 'Abjadia Store',
                    'ar' => 'متجر أبجدية'
                ],
                'site_description' => [
                    'en' => 'Your one-stop shop for books and school supplies.',
                    'ar' => 'وجهتكم المفضلة للكتب والأدوات المدرسية.'
                ],
                'site_keywords' => [
                    'en' => 'books, stationery, school supplies, abjadia',
                    'ar' => 'كتب، قرطاسية، أدوات مدرسية، أبجدية'
                ],
                'contact_email' => 'info@abjadia.com',
                'contact_phone' => '+966123456789',
                'whatsapp_number' => '+966123456789',
                'social_facebook' => 'https://facebook.com/abjadianstore',
                'social_twitter' => 'https://twitter.com/abjadianstore',
                'social_instagram' => 'https://instagram.com/abjadianstore',
                'social_linkedin' => '',
                'social_snapchat' => '',
                'social_youtube' => '',
            ]
        );
    }
}
