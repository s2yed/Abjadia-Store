<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        $authors = [
            [
                'name' => ['en' => 'Naguib Mahfouz', 'ar' => 'نجيب محفوظ'],
                'bio' => ['en' => 'Nobel Prize-winning Egyptian writer.', 'ar' => 'روائي ومؤلف مصري حائز على جائزة نوبل.']
            ],
            [
                'name' => ['en' => 'J.K. Rowling', 'ar' => 'ج. ك. رولينج'],
                'bio' => ['en' => 'British author, best known for the Harry Potter series.', 'ar' => 'مؤلفة بريطانية، اشتهرت بسلسلة هاري بوتر.']
            ],
            [
                'name' => ['en' => 'Gibran Khalil Gibran', 'ar' => 'جبران خليل جبران'],
                'bio' => ['en' => 'Lebanese-American writer, poet, and visual artist.', 'ar' => 'كاتب وشاعر وفنان تشكيلي لبناني أمريكي.']
            ],
            [
                'name' => ['en' => 'Agatha Christie', 'ar' => 'أجاثا كريستي'],
                'bio' => ['en' => 'English writer known for her sixty-six detective novels.', 'ar' => 'كاتبة إنجليزية اشتهرت برواياتها البوليسية.']
            ],
            [
                'name' => ['en' => 'Ghassan Kanafani', 'ar' => 'غسان كنفاني'],
                'bio' => ['en' => 'Palestinian author and a leading member of the PFLP.', 'ar' => 'كاتب وصحفي فلسطيني ومناضل.']
            ],
            [
                'name' => ['en' => 'Fyodor Dostoevsky', 'ar' => 'فيودور دوستويفسكي'],
                'bio' => ['en' => 'Russian novelist, philosopher, and short story writer.', 'ar' => 'روائي وفيلسوف وكاتب قصص قصيرة روسي.']
            ],
            [
                'name' => ['en' => 'Taha Hussein', 'ar' => 'طه حسين'],
                'bio' => ['en' => 'One of the most influential 20th-century Egyptian writers.', 'ar' => 'أديب وناقد مصري، لُقب بعميد الأدب العربي.']
            ],
            [
                'name' => ['en' => 'George Orwell', 'ar' => 'جورج أورويل'],
                'bio' => ['en' => 'English novelist and essayist, journalist and critic.', 'ar' => 'روائي وصحفي وناقد إنجليزي.']
            ],
            [
                'name' => ['en' => 'Mahmoud Darwish', 'ar' => 'محمود درويش'],
                'bio' => ['en' => 'Palestinian poet and author.', 'ar' => 'شاعر وأديب فلسطيني.']
            ],
            [
                'name' => ['en' => 'Paulo Coelho', 'ar' => 'باولو كويلو'],
                'bio' => ['en' => 'Brazilian lyricist and novelist.', 'ar' => 'روائي وقاص برازيلي.']
            ],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }
    }
}
