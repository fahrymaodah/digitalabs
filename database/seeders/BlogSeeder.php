<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Design Tips', 'slug' => 'design-tips', 'description' => 'Tips dan trik desain grafis'],
            ['name' => 'Motion Graphics', 'slug' => 'motion-graphics', 'description' => 'Tutorial motion graphics dan animasi'],
            ['name' => 'Microstock', 'slug' => 'microstock', 'description' => 'Panduan menghasilkan uang dari microstock'],
            ['name' => 'Workflow & Tools', 'slug' => 'workflow-tools', 'description' => 'Tools dan workflow design profesional'],
        ];

        foreach ($categories as $cat) {
            ArticleCategory::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // Get first user or create one
        $author = \App\Models\User::first();
        if (!$author) {
            $author = \App\Models\User::create([
                'name' => 'Admin DigitaLabs',
                'email' => 'admin@digitalabs.id',
                'password' => bcrypt('password'),
            ]);
        }

        // Create articles
        $articles = [
            [
                'title' => '10 Tips Desain Grafis untuk Pemula yang Ingin Profesional',
                'slug' => '10-tips-desain-grafis-pemula',
                'content' => 'Belajar desain grafis bisa dimulai dengan memahami prinsip-prinsip dasar. Artikel ini membahas 10 tips yang akan membantu Anda menjadi desainer yang lebih baik. Dari tipografi, komposisi, hingga penggunaan warna yang tepat.',
                'excerpt' => 'Panduan lengkap untuk pemula yang ingin belajar desain grafis dengan benar.',
                'author_id' => $author->id,
                'category_id' => ArticleCategory::where('slug', 'design-tips')->first()->id,
                'featured_image' => 'https://placehold.co/800x450/f97316/white?text=Design+Tips',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Cara Membuat Motion Graphics yang Menarik dengan After Effects',
                'slug' => 'motion-graphics-after-effects',
                'content' => 'Motion graphics adalah seni menggerakkan elemen visual untuk menceritakan sebuah cerita. Dalam tutorial ini, kita akan belajar membuat motion graphics dasar menggunakan Adobe After Effects dengan langkah-langkah yang mudah diikuti.',
                'excerpt' => 'Belajar membuat animasi motion graphics profesional dari nol.',
                'author_id' => $author->id,
                'category_id' => ArticleCategory::where('slug', 'motion-graphics')->first()->id,
                'featured_image' => 'https://placehold.co/800x450/f97316/white?text=Motion+Graphics',
                'status' => 'published',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Strategi Jual Assets di Microstock untuk Pemula',
                'slug' => 'strategi-jual-assets-microstock',
                'content' => 'Microstock adalah platform yang memungkinkan Anda menjual karya desain, foto, dan video dengan royalti. Artikel ini akan mengajarkan strategi yang tepat untuk memulai dan mengembangkan bisnis microstock Anda.',
                'excerpt' => 'Panduan lengkap untuk pemula yang ingin menghasilkan passive income dari microstock.',
                'author_id' => $author->id,
                'category_id' => ArticleCategory::where('slug', 'microstock')->first()->id,
                'featured_image' => 'https://placehold.co/800x450/f97316/white?text=Microstock',
                'status' => 'published',
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => '5 Tools Design yang Wajib Dimiliki Setiap Designer',
                'slug' => '5-tools-design-wajib',
                'content' => 'Sebagai designer profesional, Anda membutuhkan tools yang tepat untuk meningkatkan produktivitas. Berikut adalah 5 tools design yang wajib dimiliki untuk workflow yang lebih efisien.',
                'excerpt' => 'Rekomendasi tools design terbaik untuk workflow yang lebih cepat dan efisien.',
                'author_id' => $author->id,
                'category_id' => ArticleCategory::where('slug', 'workflow-tools')->first()->id,
                'featured_image' => 'https://placehold.co/800x450/f97316/white?text=Design+Tools',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Menjadi Fullstack Motion Designer dengan Adobe Suite',
                'slug' => 'fullstack-motion-designer',
                'content' => 'Motion designer modern perlu menguasai beberapa tools dari Adobe Suite. Artikel ini menjelaskan bagaimana cara menjadi motion designer yang komprehensif dengan menguasai Premiere Pro, After Effects, dan Character Animator.',
                'excerpt' => 'Kuasai Adobe Suite untuk menjadi motion designer yang versatile.',
                'author_id' => $author->id,
                'category_id' => ArticleCategory::where('slug', 'motion-graphics')->first()->id,
                'featured_image' => 'https://placehold.co/800x450/f97316/white?text=Adobe+Suite',
                'status' => 'published',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Fotografi Produk untuk Penjualan Online: Panduan Lengkap',
                'slug' => 'fotografi-produk-online',
                'content' => 'Fotografi produk yang baik adalah kunci untuk meningkatkan penjualan online Anda. Dalam artikel ini, kita akan membahas teknik lighting, komposisi, dan editing untuk menghasilkan foto produk yang profesional dan menarik pembeli.',
                'excerpt' => 'Teknik fotografi produk yang akan meningkatkan konversi penjualan Anda.',
                'author_id' => $author->id,
                'category_id' => ArticleCategory::where('slug', 'design-tips')->first()->id,
                'featured_image' => 'https://placehold.co/800x450/f97316/white?text=Product+Photography',
                'status' => 'published',
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
