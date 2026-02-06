<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Tutor;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Create 2 tutors
        $tutor1 = Tutor::create([
            'name' => 'Fahry Maodah',
            'slug' => 'fahry-maodah',
            'title' => 'Senior WordPress & Web Developer',
            'bio' => 'Instructor berpengalaman lebih dari 9 tahun di bidang web development, khususnya WordPress, Laravel, dan Frontend Development. Telah mengajar ribuan siswa dan membantu mereka membangun karir sebagai web developer profesional.',
            'email' => 'fahry@digitalabs.id',
            'phone' => '+62812345678',
            'website' => 'https://digitalabs.id',
            'linkedin' => 'https://linkedin.com/in/fahrymaodah',
            'youtube' => 'https://youtube.com/@digitalabs',
            'instagram' => 'https://instagram.com/digitalabs.id',
            'experience_years' => 9,
            'is_active' => true,
            'order' => 1,
        ]);

        $tutor2 = Tutor::create([
            'name' => 'Ahmad Fauzi',
            'slug' => 'ahmad-fauzi',
            'title' => 'Full Stack Developer & UI/UX Specialist',
            'bio' => 'Berpengalaman 7+ tahun sebagai Full Stack Developer dengan spesialisasi di Laravel, React, dan UI/UX Design. Passionate dalam mengajarkan konsep programming yang mudah dipahami untuk pemula hingga advanced.',
            'email' => 'fauzi@digitalabs.id',
            'phone' => '+62823456789',
            'website' => 'https://digitalabs.id',
            'linkedin' => 'https://linkedin.com/in/ahmadfauzi',
            'youtube' => 'https://youtube.com/@digitalabs',
            'instagram' => 'https://instagram.com/digitalabs.id',
            'experience_years' => 7,
            'is_active' => true,
            'order' => 2,
        ]);

        // Attach tutors to all published courses
        $courses = Course::where('status', 'published')->get();

        foreach ($courses as $index => $course) {
            // First course gets tutor1 as primary
            if ($index === 0) {
                $course->tutors()->attach($tutor1->id, ['is_primary' => true, 'order' => 1]);
                $course->tutors()->attach($tutor2->id, ['is_primary' => false, 'order' => 2]);
            } 
            // Second course gets tutor2 as primary
            elseif ($index === 1) {
                $course->tutors()->attach($tutor2->id, ['is_primary' => true, 'order' => 1]);
                $course->tutors()->attach($tutor1->id, ['is_primary' => false, 'order' => 2]);
            }
            // Other courses get both tutors (tutor1 primary)
            else {
                $course->tutors()->attach($tutor1->id, ['is_primary' => true, 'order' => 1]);
                $course->tutors()->attach($tutor2->id, ['is_primary' => false, 'order' => 2]);
            }
        }

        $this->command->info('✓ Created 2 tutors and attached to ' . $courses->count() . ' courses');
    }
}
