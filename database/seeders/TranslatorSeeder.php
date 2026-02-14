<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translator;

class TranslatorSeeder extends Seeder
{
    public function run()
    {
        $translators = [
            [
                'name' => ['en' => 'Denys Johnson-Davies', 'ar' => 'دينيس جونسون ديفيز'],
                'bio' => ['en' => 'An influential Arabic-to-English translator.', 'ar' => 'مترجم مؤثر من العربية إلى الإنجليزية.'],
                'image' => 'banners/hero_placeholder.jpg',
            ],
            [
                'name' => ['en' => 'Humphrey Davies', 'ar' => 'هامفري ديفيز'],
                'bio' => ['en' => 'Award-winning translator of Arabic literature.', 'ar' => 'مترجم حائز على جوائز للأدب العربي.'],
                'image' => 'banners/hero_placeholder.jpg',
            ],
        ];

        foreach ($translators as $translator) {
            Translator::create($translator);
        }
    }
}
