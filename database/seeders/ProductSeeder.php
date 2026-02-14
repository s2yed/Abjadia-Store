<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get Categories
        $novels = Category::where('slug', 'novels')->first();
        $academic = Category::where('slug', 'academic')->first();
        $islamic = Category::where('slug', 'islamic')->first();
        $kids = Category::where('slug', 'children')->first();

        $notebooks = Category::where('slug', 'notebooks')->first();
        $pens = Category::where('slug', 'pens-pencils')->first();
        $art = Category::where('slug', 'art-supplies')->first();
        $backpacks = Category::where('slug', 'backpacks')->first();

        // Get Authors, Translators, Publishers
        $authors = Author::all();
        $translators = \App\Models\Translator::all();
        $publishers = \App\Models\Publisher::all();
        $brands = \App\Models\Brand::all();

        // --- BOOKS ---
        $books = [
            [
                'name' => ['en' => 'The Cairo Trilogy', 'ar' => 'ثلاثية القاهرة'],
                'description' => ['en' => 'A masterpiece by Naguib Mahfouz depicting life in Cairo.', 'ar' => 'تحفة أدبية لنجيب محفوظ تصور الحياة في القاهرة.'],
                'price' => 25.00,
                'discount_price' => 20.00,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'Naguib Mahfouz', 'ar' => 'نجيب محفوظ'],
            ],
            [
                'name' => ['en' => 'Harry Potter and the Philosopher\'s Stone', 'ar' => 'هاري بوتر وحجر الفيلسوف'],
                'description' => ['en' => 'The first novel in the Harry Potter series and J. K. Rowling\'s debut novel.', 'ar' => 'الرواية الأولى في سلسلة هاري بوتر للكاتبة ج. ك. رولينج.'],
                'price' => 30.00,
                'discount_price' => null,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'J.K. Rowling', 'ar' => 'ج. ك. رولينج'],
            ],
            [
                'name' => ['en' => 'The Prophet', 'ar' => 'النبي'],
                'description' => ['en' => 'A book of 26 prose poetry fables written in English by Kahlil Gibran.', 'ar' => 'كتاب يضم 26 قصيدة نثرية كتبها جبران خليل جبران.'],
                'price' => 15.00,
                'discount_price' => 12.00,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'Gibran Khalil Gibran', 'ar' => 'جبران خليل جبران'],
            ],
            [
                'name' => ['en' => 'Murder on the Orient Express', 'ar' => 'جريمة في قطار الشرق السريع'],
                'description' => ['en' => 'A detective novel by English writer Agatha Christie featuring the Belgian detective Hercule Poirot.', 'ar' => 'رواية بوليسية للكاتبة أجاثا كريستي من بطولة هيركيول بوارو.'],
                'price' => 18.00,
                'discount_price' => null,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'Agatha Christie', 'ar' => 'أجاثا كريستي'],
            ],
            [
                'name' => ['en' => 'Men in the Sun', 'ar' => 'رجال في الشمس'],
                'description' => ['en' => 'A novel by Ghassan Kanafani.', 'ar' => 'رواية للكاتب الفلسطيني غسان كنفاني.'],
                'price' => 12.00,
                'discount_price' => 10.00,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'Ghassan Kanafani', 'ar' => 'غسان كنفاني'],
            ],
            [
                'name' => ['en' => 'Crime and Punishment', 'ar' => 'الجريمة والعقاب'],
                'description' => ['en' => 'A novel by the Russian author Fyodor Dostoevsky.', 'ar' => 'رواية شهيرة للكاتب الروسي فيودور دوستويفسكي.'],
                'price' => 22.00,
                'discount_price' => null,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'Fyodor Dostoevsky', 'ar' => 'فيودور دوستويفسكي'],
            ],
            [
                'name' => ['en' => 'The Days', 'ar' => 'الأيام'],
                'description' => ['en' => 'An autobiography by Taha Hussein.', 'ar' => 'سيرة ذاتية عميقة لعميد الأدب العربي طه حسين.'],
                'price' => 20.00,
                'discount_price' => 18.00,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'Taha Hussein', 'ar' => 'طه حسين'],
            ],
            [
                'name' => ['en' => '1984', 'ar' => '١٩٨٤'],
                'description' => ['en' => 'A dystopian social fiction novel by English novelist George Orwell.', 'ar' => 'رواية ديستوبية شهيرة للكاتب جورج أورويل.'],
                'price' => 15.00,
                'discount_price' => null,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'George Orwell', 'ar' => 'جورج أورويل'],
            ],
            [
                'name' => ['en' => 'Memory for Forgetfulness', 'ar' => 'ذاكرة للنسيان'],
                'description' => ['en' => 'Prose poem by Mahmoud Darwish.', 'ar' => 'عمل أدبي رائع للشاعر محمود درويش.'],
                'price' => 14.00,
                'discount_price' => null,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'Mahmoud Darwish', 'ar' => 'محمود درويش'],
            ],
            [
                'name' => ['en' => 'The Alchemist', 'ar' => 'الخيميائي'],
                'description' => ['en' => 'A novel by Brazilian author Paulo Coelho.', 'ar' => 'رواية ملهمة للكاتب باولو كويلو.'],
                'price' => 16.00,
                'discount_price' => 14.50,
                'category_id' => $novels->id ?? 1,
                'author_name' => ['en' => 'Paulo Coelho', 'ar' => 'باولو كويلو'],
            ],
            [
                'name' => ['en' => 'Introduction to Algorithms', 'ar' => 'مقدمة في الخوارزميات'],
                'description' => ['en' => 'Comprehensive guide to computer algorithms.', 'ar' => 'دليل شامل لتعلم خوارزميات الحاسوب.'],
                'price' => 80.00,
                'discount_price' => null,
                'category_id' => $academic->id ?? 1,
            ],
            [
                'name' => ['en' => 'The Holy Quran (Tajweed)', 'ar' => 'القرآن الكريم (تجويد)'],
                'description' => ['en' => 'Color coded Quran for Tajweed.', 'ar' => 'مصحف ملون للمساعدة في أحكام التجويد.'],
                'price' => 35.00,
                'discount_price' => 30.00,
                'category_id' => $islamic->id ?? 1,
            ],
            [
                'name' => ['en' => 'Bedtime Stories for Kids', 'ar' => 'قصص قبل النوم للأطفال'],
                'description' => ['en' => 'Collection of 50 short stories.', 'ar' => 'مجموعة من ٥٠ قصة قصيرة للأطفال.'],
                'price' => 20.00,
                'discount_price' => null,
                'category_id' => $kids->id ?? 1,
            ],
        ];

        foreach ($books as $bookData) {
            $authorNameEn = $bookData['author_name']['en'] ?? null;
            unset($bookData['author_name']);

            $bookNameEn = $bookData['name']['en'];
            $image = 'products/book.png';
            if (Str::contains($bookNameEn, 'Harry Potter')) $image = 'products/harry_potter.png';
            if (Str::contains($bookNameEn, 'Cairo Trilogy')) $image = 'products/cairo_trilogy.png';
            if (Str::contains($bookNameEn, '1984')) $image = 'products/1984.png';
            if (Str::contains($bookNameEn, 'Quran')) $image = 'products/quran.png';
            if (Str::contains($bookNameEn, 'Alchemist')) $image = 'products/alchemist.png';
            if (Str::contains($bookNameEn, 'The Prophet')) $image = 'products/prophet.png';
            if (Str::contains($bookNameEn, 'Orient Express')) $image = 'products/orient_express.png';
            if (Str::contains($bookNameEn, 'Crime and Punishment')) $image = 'products/crime_punishment.png';
            if (Str::contains($bookNameEn, 'Algorithms')) $image = 'products/algorithms.png';
            if (Str::contains($bookNameEn, 'Bedtime Stories')) $image = 'products/bedtime_stories.png';

            $product = Product::create(array_merge($bookData, [
                'slug' => Str::slug($bookNameEn) . '-' . rand(1000, 9999),
                'stock' => rand(10, 100),
                'is_active' => true,
                'type' => 'book',
                'isbn' => rand(1000000000, 9999999999),
                'pages' => rand(100, 800),
                'publication_year' => rand(1950, 2024),
                'image' => $image,
                'publisher_id' => $publishers->random()->id ?? null,
                'brand_id' => $brands->random()->id ?? null,
            ]));

            if ($authorNameEn) {
                // Find author by English name
                $author = Author::all()->first(function ($a) use ($authorNameEn) {
                    return $a->getTranslation('name', 'en') === $authorNameEn;
                });
                if ($author) {
                    $product->authors()->attach($author->id);
                }
            }
        }

        // --- SUPPLIES ---
        $supplies = [
            [
                'name' => ['en' => 'Premium A5 Notebook', 'ar' => 'دفتر A5 فاخر'],
                'description' => ['en' => 'High quality A5 notebook for your daily needs.', 'ar' => 'دفتر عالي الجودة مقاس A5 لجميع احتياجاتك.'],
                'price' => 12.00,
                'cat' => $notebooks
            ],
            [
                'name' => ['en' => 'Moleskine Classic Notebook', 'ar' => 'دفتر مولسكين كلاسيكي'],
                'description' => ['en' => 'A legendary notebook used by artists and thinkers.', 'ar' => 'دفتر أسطوري يستخدمه الفنانون والمفكرون.'],
                'price' => 22.00,
                'cat' => $notebooks
            ],
            [
                'name' => ['en' => 'Parker Jotter Ballpoint Pen', 'ar' => 'قلم جاف باركر'],
                'description' => ['en' => 'Reliable and iconic writing instrument.', 'ar' => 'أداة كتابة موثوقة وأيقونية.'],
                'price' => 18.00,
                'cat' => $pens
            ],
            [
                'name' => ['en' => 'Faber-Castell Color Pencils', 'ar' => 'أقلام تلوين فابر كاستل'],
                'description' => ['en' => 'Premium watercolor pencils for artists.', 'ar' => 'أقلام تلوين مائية فاخرة للفنانين.'],
                'price' => 15.00,
                'cat' => $art
            ],
            [
                'name' => ['en' => 'Orthopedic School Backpack', 'ar' => 'حقيبة مدرسية طبية'],
                'description' => ['en' => 'Comfortable and durable backpack for students.', 'ar' => 'حقيبة ظهر مريحة ومتينة للطلاب.'],
                'price' => 45.00,
                'cat' => $backpacks
            ],
            [
                'name' => ['en' => 'Scientific Calculator', 'ar' => 'آلة حاسبة علمية'],
                'description' => ['en' => 'Advanced features for complex mathematical equations.', 'ar' => 'ميزات متقدمة للمعادلات الرياضية المعقدة.'],
                'price' => 30.00,
                'cat' => $backpacks
            ],
        ];

        foreach ($supplies as $item) {
            $supplyNameEn = $item['name']['en'];
            $image = 'products/stationery.png';
            if (Str::contains($supplyNameEn, 'Pencils')) $image = 'products/pencils.png';
            if (Str::contains($supplyNameEn, 'Backpack')) $image = 'products/backpack.png';
            if (Str::contains($supplyNameEn, 'Calculator')) $image = 'products/calculator.png';
            if (Str::contains($supplyNameEn, 'Moleskine')) $image = 'products/moleskine.png';
            if (Str::contains($supplyNameEn, 'Parker')) $image = 'products/parker_pen.png';

            Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($supplyNameEn) . '-' . rand(1000, 9999),
                'description' => $item['description'],
                'price' => $item['price'],
                'stock' => rand(20, 200),
                'is_active' => true,
                'type' => 'supply',
                'category_id' => $item['cat']->id ?? 2,
                'image' => $image,
            ]);
        }
    }
}
