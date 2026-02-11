<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about-us',
                'title' => [
                    'en' => 'About Us',
                    'ar' => 'من نحن',
                ],
                'content' => [
                    'en' => '<h1>About Abjadia Store</h1><p>Welcome to Abjadia Store, your number one source for all books and school supplies. We\'re dedicated to giving you the very best of products, with a focus on dependability, customer service and uniqueness.</p><p>Founded in 2026, Abjadia Store has come a long way from its beginnings. When we first started out, our passion for reading drove us to do intense research, and gave us the impetus to turn hard work and inspiration into to a booming online store. We now serve customers all over the region, and are thrilled to be a part of the eco-friendly wing of the industry.</p><p>We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don\'t hesitate to contact us.</p>',
                    'ar' => '<h1>عن متجر أبجدية</h1><p>مرحبًا بكم في متجر أبجدية، مصدركم الأول لجميع الكتب والمستلزمات المدرسية. نحن ملتزمون بتقديم أفضل المنتجات لكم، مع التركيز على الاعتمادية وخدمة العملاء والتميز.</p><p>تأسس متجر أبجدية في عام 2026، وقد قطع شوطًا طويلاً منذ بداياته. عندما بدأنا لأول مرة، دفعنا شغفنا بالقراءة إلى إجراء أبحاث مكثفة، وأعطانا الدافع لتحويل العمل الجاد والإلهام إلى متجر إلكتروني مزدهر. نحن الآن نخدم العملاء في جميع أنحاء المنطقة، ويسعدنا أن نكون جزءًا من الجناح الصديق للبيئة في الصناعة.</p><p>نأمل أن تستمتعوا بمنتجاتنا بقدر ما نستمتع بتقديمها لكم. إذا كان لديكم أي أسئلة أو تعليقات، لا تترددوا في الاتصال بنا.</p>',
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'contact-us',
                'title' => [
                    'en' => 'Contact Us',
                    'ar' => 'اتصل بنا',
                ],
                'content' => [
                    'en' => '<h1>Contact Us</h1><p>We would love to hear from you!</p><ul><li><strong>Email:</strong> support@abjadiastore.com</li><li><strong>Phone:</strong> +966 12 345 6789</li><li><strong>Address:</strong> Riyadh, Saudi Arabia</li></ul><p>Feel free to reach out to us through any of the channels above or use the chat icon on the page.</p>',
                    'ar' => '<h1>اتصل بنا</h1><p>يسعدنا سماع صوتك!</p><ul><li><strong>البريد الإلكتروني:</strong> support@abjadiastore.com</li><li><strong>الهاتف:</strong> +966 12 345 6789</li><li><strong>العنوان:</strong> الرياض، المملكة العربية السعودية</li></ul><p>لا تتردد في التواصل معنا عبر أي من القنوات المذكورة أعلاه أو استخدام أيقونة الدردشة الموجودة في الصفحة.</p>',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
