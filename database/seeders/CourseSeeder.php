<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $videoEditingCategory = CourseCategory::where('slug', 'video-editing')->first();
        $webDevCategory = CourseCategory::where('slug', 'web-development')->first();

        // Course 1: Video Editing
        $course1 = Course::create([
            'category_id' => $videoEditingCategory->id,
            'title' => 'Mastering Adobe Premiere Pro',
            'slug' => 'mastering-adobe-premiere-pro',
            'description' => 'Pelajari Adobe Premiere Pro dari dasar hingga mahir. Cocok untuk pemula yang ingin menjadi video editor profesional.',
            'content' => '<h2>Apa yang akan kamu pelajari?</h2>
<ul>
<li>Dasar-dasar editing video</li>
<li>Teknik cutting dan transisi</li>
<li>Color grading profesional</li>
<li>Audio editing dan mixing</li>
<li>Export untuk berbagai platform</li>
</ul>',
            'thumbnail' => null,
            'price' => 500000,
            'sale_price' => 350000,
            'preview_url' => 'https://www.youtube.com/watch?v=example1',
            'status' => 'published',
            'access_type' => 'lifetime',
            'order' => 1,
        ]);

        // Sections for Course 1
        $section1 = Section::create([
            'course_id' => $course1->id,
            'title' => 'Pendahuluan',
            'description' => 'Perkenalan dengan Adobe Premiere Pro',
            'order' => 1,
        ]);

        Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Selamat Datang di Kursus',
            'description' => 'Perkenalan dengan kursus dan materi yang akan dipelajari',
            'youtube_url' => 'https://www.youtube.com/watch?v=example1',
            'duration' => 300, // 5 minutes
            'order' => 1,
            'is_free' => true,
        ]);

        Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Install Adobe Premiere Pro',
            'description' => 'Cara download dan install Adobe Premiere Pro',
            'youtube_url' => 'https://www.youtube.com/watch?v=example2',
            'duration' => 600, // 10 minutes
            'order' => 2,
            'is_free' => true,
        ]);

        $section2 = Section::create([
            'course_id' => $course1->id,
            'title' => 'Dasar-dasar Editing',
            'description' => 'Belajar fundamental editing video',
            'order' => 2,
        ]);

        Lesson::create([
            'section_id' => $section2->id,
            'title' => 'Interface Premiere Pro',
            'description' => 'Mengenal tampilan dan tools di Premiere Pro',
            'youtube_url' => 'https://www.youtube.com/watch?v=example3',
            'duration' => 900, // 15 minutes
            'order' => 1,
            'is_free' => false,
        ]);

        Lesson::create([
            'section_id' => $section2->id,
            'title' => 'Import Media',
            'description' => 'Cara import video, audio, dan gambar',
            'youtube_url' => 'https://www.youtube.com/watch?v=example4',
            'duration' => 720, // 12 minutes
            'order' => 2,
            'is_free' => false,
        ]);

        Lesson::create([
            'section_id' => $section2->id,
            'title' => 'Basic Cutting',
            'description' => 'Teknik dasar memotong video',
            'youtube_url' => 'https://www.youtube.com/watch?v=example5',
            'duration' => 1200, // 20 minutes
            'order' => 3,
            'is_free' => false,
        ]);

        // Course 2: Web Development
        $course2 = Course::create([
            'category_id' => $webDevCategory->id,
            'title' => 'Full Stack Laravel & Vue.js',
            'slug' => 'full-stack-laravel-vuejs',
            'description' => 'Bangun aplikasi web modern dengan Laravel dan Vue.js. Dari backend hingga frontend, semua dalam satu kursus.',
            'content' => '<h2>Apa yang akan kamu pelajari?</h2>
<ul>
<li>Laravel fundamentals</li>
<li>Vue.js dan Composition API</li>
<li>REST API development</li>
<li>Authentication & Authorization</li>
<li>Deployment ke production</li>
</ul>',
            'thumbnail' => null,
            'price' => 750000,
            'sale_price' => null,
            'preview_url' => 'https://www.youtube.com/watch?v=example6',
            'status' => 'published',
            'access_type' => 'lifetime',
            'order' => 2,
        ]);

        $section3 = Section::create([
            'course_id' => $course2->id,
            'title' => 'Setup Development Environment',
            'description' => 'Siapkan tools untuk development',
            'order' => 1,
        ]);

        Lesson::create([
            'section_id' => $section3->id,
            'title' => 'Pengenalan Kursus',
            'description' => 'Overview materi yang akan dipelajari',
            'youtube_url' => 'https://www.youtube.com/watch?v=example7',
            'duration' => 420, // 7 minutes
            'order' => 1,
            'is_free' => true,
        ]);

        Lesson::create([
            'section_id' => $section3->id,
            'title' => 'Install PHP & Composer',
            'description' => 'Setup PHP dan Composer di komputer',
            'youtube_url' => 'https://www.youtube.com/watch?v=example8',
            'duration' => 900, // 15 minutes
            'order' => 2,
            'is_free' => false,
        ]);
    }
}
