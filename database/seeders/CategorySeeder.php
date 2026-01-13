<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => ['en' => 'Books', 'ar' => 'كتب'],
                'description' => ['en' => 'A wide collection of books from various genres.', 'ar' => 'مجموعة واسعة من الكتب بكافة أنواعها.'],
                'slug' => 'books',
                'image' => 'images/categories/books.png',
                'children' => [
                    ['name' => ['en' => 'Novels', 'ar' => 'روايات'], 'description' => ['en' => 'Fiction and storytelling masterpieces.', 'ar' => 'روايات وقصص أدبية.']],
                    ['name' => ['en' => 'Academic', 'ar' => 'كتب أكاديمية'], 'description' => ['en' => 'Textbooks and educational resources.', 'ar' => 'كتب مدرسية وموارد تعليمية.']],
                    ['name' => ['en' => 'Islamic', 'ar' => 'كتب إسلامية'], 'description' => ['en' => 'Religious texts and Islamic literature.', 'ar' => 'كتب دينية وإسلامية.']],
                    ['name' => ['en' => 'Children', 'ar' => 'كتب أطفال'], 'description' => ['en' => 'Books for kids and young readers.', 'ar' => 'كتب للأطفال واليافعين.']],
                    ['name' => ['en' => 'Self-Help', 'ar' => 'تطوير الذات'], 'description' => ['en' => 'Books for personal development.', 'ar' => 'كتب لتطوير الذات والمهارات الشخصية.']],
                ]
            ],
            [
                'name' => ['en' => 'Stationery', 'ar' => 'قرطاسية'],
                'description' => ['en' => 'High quality stationery for office and school.', 'ar' => 'قرطاسية عالية الجودة للمكتب والمدرسة.'],
                'slug' => 'stationery',
                'image' => 'images/categories/stationery.png',
                'children' => [
                    ['name' => ['en' => 'Notebooks', 'ar' => 'دفاتر'], 'description' => ['en' => 'Premium notebooks and journals.', 'ar' => 'دفاتر ومفكرات فاخرة.']],
                    ['name' => ['en' => 'Pens & Pencils', 'ar' => 'أقلام'], 'description' => ['en' => 'Writing instruments from top brands.', 'ar' => 'أدوات كتابة من ماركات عالمية.']],
                    ['name' => ['en' => 'Art Supplies', 'ar' => 'أدوات فنية'], 'description' => ['en' => 'Tools for creativity and art.', 'ar' => 'أدوات للفنون والإبداع.']],
                ]
            ],
            [
                'name' => ['en' => 'School Supplies', 'ar' => 'مستلزمات مدرسية'],
                'description' => ['en' => 'Everything a student needs for school.', 'ar' => 'كل ما يحتاجه الطالب للمدرسة.'],
                'slug' => 'school-supplies',
                'image' => 'images/categories/school-supplies.png',
                'children' => [
                    ['name' => ['en' => 'Backpacks', 'ar' => 'حقائب مدرسية'], 'description' => ['en' => 'Durable and stylish school bags.', 'ar' => 'حقائب مدرسية متينة وأنيقة.']],
                    ['name' => ['en' => 'Geometry Sets', 'ar' => 'أدوات هندسية'], 'description' => ['en' => 'Mathematical tools.', 'ar' => 'أدوات وعلب هندسية.']],
                    ['name' => ['en' => 'Pencil Cases', 'ar' => 'مقالم'], 'description' => ['en' => 'Organizers for your stationery.', 'ar' => 'مقالم ومنظمات للأدوات.']],
                ]
            ],
            [
                'name' => ['en' => 'Office Essentials', 'ar' => 'مستلزمات مكتبية'],
                'description' => ['en' => 'Supplies to keep your office running smoothly.', 'ar' => 'مستلزمات لتسهيل العمل المكتبي.'],
                'slug' => 'office-essentials',
                'image' => 'images/categories/office-essentials.png',
                'children' => []
            ],
            [
                'name' => ['en' => 'Art Supplies', 'ar' => 'أدوات فنية'],
                'description' => ['en' => 'Tools for creativity and professional art.', 'ar' => 'أدوات للفنون والإبداع المحترف.'],
                'slug' => 'art-supplies-parent',
                'image' => 'images/categories/art-supplies.png',
                'children' => []
            ],
            [
                'name' => ['en' => 'Gifts & Wrap', 'ar' => 'هدايا وتغليف'],
                'description' => ['en' => 'Beautiful gifts and wrapping solutions.', 'ar' => 'هدايا مميزة وحلول تغليف أنيقة.'],
                'slug' => 'gifts-wrap',
                'image' => 'images/categories/gifts-wrap.png',
                'children' => []
            ],
            [
                'name' => ['en' => 'Office Furniture', 'ar' => 'أثاث مكتبي'],
                'description' => ['en' => 'Ergonomic furniture for your workspace.', 'ar' => 'أثاث مريح لمساحة عملك.'],
                'slug' => 'office-furniture',
                'image' => 'images/categories/office-furniture.png',
                'children' => []
            ],
            [
                'name' => ['en' => 'Electronics', 'ar' => 'إلكترونيات'],
                'description' => ['en' => 'Tech gadgets and office electronics.', 'ar' => 'أجهزة تقنية وإلكترونيات مكتبية.'],
                'slug' => 'electronics',
                'image' => 'images/categories/electronics.png',
                'children' => []
            ],
            [
                'name' => ['en' => 'Games', 'ar' => 'ألعاب'],
                'description' => ['en' => 'Educational and fun games.', 'ar' => 'ألعاب تعليمية وترفيهية.'],
                'slug' => 'games',
                'image' => 'images/categories/games.png',
                'children' => []
            ],
            [
                'name' => ['en' => 'Magazines', 'ar' => 'مجلات'],
                'description' => ['en' => 'Latest issues of popular magazines.', 'ar' => 'أحدث أعداد المجلات الشهيرة.'],
                'slug' => 'magazines',
                'image' => 'images/categories/magazines.png',
                'children' => []
            ],
            [
                'name' => ['en' => 'Music & Movies', 'ar' => 'موسيقى وأفلام'],
                'description' => ['en' => 'A collection of classics and new releases.', 'ar' => 'مجموعة من الكلاسيكيات والإصدارات الجديدة.'],
                'slug' => 'music-movies',
                'image' => 'images/categories/music-movies.png',
                'children' => []
            ],
            [
                'name' => ['en' => 'Accessories', 'ar' => 'إكسسوارات'],
                'description' => ['en' => 'Must-have accessories for your desk.', 'ar' => 'إكسسوارات لا غنى عنها لمكتبك.'],
                'slug' => 'accessories',
                'image' => 'images/categories/accessories.png',
                'children' => []
            ]
        ];

        foreach ($categories as $catData) {
            $parent = Category::create([
                'name' => $catData['name'],
                'slug' => $catData['slug'] ?? Str::slug($catData['name']['en']),
                'description' => $catData['description'],
                'image' => $catData['image'] ?? null,
                'is_active' => true,
            ]);

            foreach ($catData['children'] as $childData) {
                Category::create([
                    'name' => $childData['name'],
                    'slug' => $childData['slug'] ?? Str::slug($childData['name']['en']),
                    'description' => $childData['description'],
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
