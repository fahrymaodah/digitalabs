<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Video Editing',
                'slug' => 'video-editing',
                'description' => 'Pelajari cara edit video profesional',
                'icon' => 'heroicon-o-video-camera',
                'order' => 1,
            ],
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Kursus pemrograman web dari dasar hingga mahir',
                'icon' => 'heroicon-o-code-bracket',
                'order' => 2,
            ],
            [
                'name' => 'Mobile Development',
                'slug' => 'mobile-development',
                'description' => 'Belajar membuat aplikasi mobile',
                'icon' => 'heroicon-o-device-phone-mobile',
                'order' => 3,
            ],
            [
                'name' => 'Desain Grafis',
                'slug' => 'desain-grafis',
                'description' => 'Kuasai tools desain profesional',
                'icon' => 'heroicon-o-paint-brush',
                'order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }
    }
}
