<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'title' => 'Video Editor',
                'avatar' => null,
                'content' => 'Kursus yang luar biasa! Saya berhasil mendapat pekerjaan sebagai video editor setelah menyelesaikan kursus ini. Materi sangat lengkap dan mudah dipahami.',
                'rating' => 5,
                'is_published' => true,
                'order' => 1,
            ],
            [
                'name' => 'Siti Rahayu',
                'title' => 'Content Creator',
                'avatar' => null,
                'content' => 'Sekarang saya bisa edit video sendiri untuk channel YouTube saya. Penjelasan instrukturnya sangat jelas dan praktis.',
                'rating' => 5,
                'is_published' => true,
                'order' => 2,
            ],
            [
                'name' => 'Andi Pratama',
                'title' => 'Web Developer',
                'avatar' => null,
                'content' => 'Kursus Laravel terbaik yang pernah saya ikuti. Project-based learning yang membuat saya langsung bisa praktek.',
                'rating' => 5,
                'is_published' => true,
                'order' => 3,
            ],
            [
                'name' => 'Dewi Lestari',
                'title' => 'Freelancer',
                'avatar' => null,
                'content' => 'Investasi yang sangat worth it! Sekarang skill saya meningkat dan income freelance juga naik.',
                'rating' => 4,
                'is_published' => true,
                'order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
