<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseReview;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewAndTestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get course and users
        $course = Course::first();
        $users = User::where('id', '!=', 1)->limit(30)->get();

        if ($course && $users->count() > 0) {
            // Review texts array with 30 unique reviews
            $reviewTexts = [
                'Kursus ini sangat comprehensive dan mudah diikuti. Instruktur menjelaskan dengan detail dan memberikan banyak contoh praktis.',
                'Materi yang diberikan sangat relevan dengan kebutuhan industri saat ini. Content quality super baik dan terstruktur dengan baik.',
                'Kursus ini memberikan nilai yang sangat baik. Saya sudah belajar banyak hal baru yang bisa langsung diaplikasikan.',
                'Worth every penny! Instruktur sangat responsif dan membantu dalam setiap pertanyaan saya.',
                'Konten sangat bagus dan helpful. Daya support dari komunitas juga sangat responsif dan supportive.',
                'Saya sangat puas dengan kursus ini. Video tutorial berkualitas HD dan narasi sangat jelas dan mudah dipahami.',
                'Pembimbing sangat baik dan responsif. Saya bisa bertanya kapan saja dan selalu mendapat jawaban yang memuaskan.',
                'Ini adalah investasi terbaik untuk pengembangan skill saya. Materi sangat praktis dengan banyak studi kasus nyata.',
                'Kualitas konten luar biasa! Instruktur menjelaskan konsep kompleks dengan cara yang sangat simpel dan mudah dimengerti.',
                'Sangat worth it! Bukan cuma dapat kursus, tapi juga akses ke komunitas yang supportive dan collaborative.',
                'Saya sudah coba beberapa kursus online, tapi ini adalah yang paling comprehensive dan terstruktur dengan sangat baik.',
                'Video production quality sangat tinggi. Subtitle juga tersedia yang membuat saya bisa belajar dengan lebih fleksibel.',
                'Instruktur benar-benar ahli di bidangnya. Banyak sharing pengalaman praktis dari lapangan yang sangat valuable.',
                'Saya sudah selesai kursus ini dan langsung bisa apply ilmunya untuk project freelance dengan hasil yang memuaskan.',
                'Kursus ini mengubah perspektif saya tentang industri ini. Sekarang saya punya roadmap yang jelas untuk career development.',
                'Materi terupdate dan selalu mengikuti trend terbaru. Instruktur rutin mengupdate course dengan konten-konten baru yang relevant.',
                'Community forum sangat aktif dan helpful. Setiap pertanyaan dijawab dengan cepat dan detail oleh instruktur dan member.',
                'Saya merekomendasikan kursus ini ke semua teman-teman saya. Mereka semua jadi senang dan merasakan manfaatnya.',
                'Harga sangat reasonable untuk kualitas yang diberikan. Ini paling value for money dibanding kursus lain di market.',
                'Instruktur punya cara mengajar yang sangat engaging. Saya tidak pernah merasa bosan saat mengikuti setiap session.',
                'Saya sudah belajar dari berbagai sumber, tapi DigitaLabs punya cara tersendiri yang membuat semua jadi clear.',
                'Portfolio project yang ada di kursus sangat helpful untuk portofolio saya. Client jadi lebih tertarik dengan work saya.',
                'Kursus ini diajarkan dengan very systematic approach. Dari fundamental sampai advanced, semua terstruktur dengan sangat baik.',
                'Support team sangat responsif dan helpful. Ketika ada issue dengan akses, mereka langsung bantu solve masalahnya.',
                'Saya puas dengan progress saya setelah mengikuti kursus ini. Skill saya sudah meningkat drastis dalam beberapa minggu.',
                'Instruktur tidak hanya mengajar, tapi juga memberikan guidance untuk career development yang sangat helpful.',
                'Video quality dan production value sangat professional. Terlihat instruktur dan team benar-benar peduli dengan kualitas.',
                'Banyak resource tambahan yang disediakan selain video. PDF, template, tools semua ada dan sangat membantu pembelajaran.',
                'Saya bisa belajar dengan pace saya sendiri. Tidak ada deadline yang ketat, jadi saya bisa balance dengan pekerjaan lain.',
                'Course ini benar-benar game changer untuk saya. Terima kasih DigitaLabs untuk sharing ilmu yang sangat valuable!'
            ];

            $ratings = [5, 5, 4, 5, 4, 5, 5, 5, 5, 4, 5, 5, 5, 5, 5, 5, 5, 4, 5, 5, 5, 4, 5, 5, 5, 5, 5, 5, 5, 5];

            foreach ($ratings as $key => $rating) {
                if ($key < $users->count()) {
                    CourseReview::create([
                        'course_id' => $course->id,
                        'user_id' => $users[$key]->id,
                        'rating' => $rating,
                        'review' => $reviewTexts[$key],
                        'is_published' => true,
                        'published_at' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }

        // Create testimonials
        $testimonials = [
            ['name' => 'Ardi Septiawan', 'title' => 'Freelance Designer', 'avatar' => 'https://i.pravatar.cc/150?img=1', 'content' => 'Kursus DigitaLabs benar-benar mengubah cara saya bekerja. Saya sekarang bisa handle project yang lebih kompleks dengan confidence tinggi.', 'rating' => 5, 'is_published' => 1, 'order' => 1],
            ['name' => 'Putu Pasek Rentijaya', 'title' => 'Motion Graphics Artist', 'avatar' => 'https://i.pravatar.cc/150?img=2', 'content' => 'Saya sudah coba banyak kursus online, tapi DigitaLabs adalah yang paling comprehensive dan detailed dalam pengajarannya.', 'rating' => 5, 'is_published' => 1, 'order' => 2],
            ['name' => 'Alantio', 'title' => 'UI/UX Designer', 'avatar' => 'https://i.pravatar.cc/150?img=3', 'content' => 'Instruktur sangat knowledgeable dan cara mengajarnya membuat pembelajaran jadi fun dan engaging untuk setiap sesi.', 'rating' => 5, 'is_published' => 1, 'order' => 3],
            ['name' => 'Kopi Jahe', 'title' => 'Content Creator', 'avatar' => 'https://i.pravatar.cc/150?img=4', 'content' => 'DigitaLabs memberikan structure learning yang perfect dengan live session, Q&A, dan community support yang sangat helpful.', 'rating' => 5, 'is_published' => 1, 'order' => 4],
            ['name' => 'Sarah Kusuma', 'title' => 'Entrepreneur', 'avatar' => 'https://i.pravatar.cc/150?img=5', 'content' => 'Value yang saya dapat dari kursus ini jauh lebih besar dari harga yang saya bayarkan. Semua materi praktis dan actionable.', 'rating' => 5, 'is_published' => 1, 'order' => 5],
            ['name' => 'Rendra Wijaya', 'title' => 'Brand Strategist', 'avatar' => 'https://i.pravatar.cc/150?img=6', 'content' => 'Kualitas course DigitaLabs sangat profesional. Setiap materi dirancang dengan matang dan relevan dengan industri terkini.', 'rating' => 5, 'is_published' => 1, 'order' => 6],
            ['name' => 'Vina Maharani', 'title' => 'Video Producer', 'avatar' => 'https://i.pravatar.cc/150?img=7', 'content' => 'Saya sangat terkesan dengan production quality dan detail instruction. Ini adalah masterclass dari industry experts yang sebenarnya.', 'rating' => 5, 'is_published' => 1, 'order' => 7],
            ['name' => 'Hendrikus Santoso', 'title' => 'Digital Marketing Manager', 'avatar' => 'https://i.pravatar.cc/150?img=8', 'content' => 'Kursus ini mengajarkan soft skill dan hard skill sekaligus dengan balanced approach yang membuat saya grow di berbagai aspek.', 'rating' => 5, 'is_published' => 1, 'order' => 8],
            ['name' => 'Lucia Puspita', 'title' => 'Web Designer', 'avatar' => 'https://i.pravatar.cc/150?img=9', 'content' => 'Saya sudah mengikuti kursus ini dan merekomendasikan ke banyak orang. Feedback semua positive dan mereka upgrade skill dengan cepat.', 'rating' => 5, 'is_published' => 1, 'order' => 9],
            ['name' => 'Bimo Prakoso', 'title' => 'Creative Director', 'avatar' => 'https://i.pravatar.cc/150?img=10', 'content' => 'Instruktur memiliki pengalaman bertahun-tahun dan sharing pengalaman praktikal yang sangat berharga dan benar-benar actionable.', 'rating' => 5, 'is_published' => 1, 'order' => 10],
            ['name' => 'Devi Lestari', 'title' => 'Graphic Designer', 'avatar' => 'https://i.pravatar.cc/150?img=11', 'content' => 'DigitaLabs membantu saya transition dari career yang berbeda. Kursus ini beginner-friendly tapi juga comprehensive untuk yang advanced.', 'rating' => 5, 'is_published' => 1, 'order' => 11],
            ['name' => 'Irfan Hamdani', 'title' => 'Animator', 'avatar' => 'https://i.pravatar.cc/150?img=12', 'content' => 'Komunitas DigitaLabs sangat supportive dan collaborative. Saya belajar dari instruktur dan juga dari member lain di komunitas.', 'rating' => 4, 'is_published' => 1, 'order' => 12],
            ['name' => 'Maya Rahma', 'title' => 'Photography Director', 'avatar' => 'https://i.pravatar.cc/150?img=13', 'content' => 'Saya berhasil mendapat project premium setelah mengikuti kursus ini. Portfolio saya jadi lebih strong dan client percaya dengan skill saya.', 'rating' => 5, 'is_published' => 1, 'order' => 13],
            ['name' => 'Aditya Nugroho', 'title' => 'Product Designer', 'avatar' => 'https://i.pravatar.cc/150?img=14', 'content' => 'Materi terupdate mengikuti perkembangan industri terkini. Tidak ketinggalan dengan trend dan best practices yang sedang berlaku sekarang.', 'rating' => 5, 'is_published' => 1, 'order' => 14],
            ['name' => 'Rizka Amalia', 'title' => 'Content Strategist', 'avatar' => 'https://i.pravatar.cc/150?img=15', 'content' => 'ROI dari kursus ini sangat tinggi. Investment yang saya keluarkan kembali dalam beberapa project pertama yang saya tangani dengan sukses.', 'rating' => 5, 'is_published' => 1, 'order' => 15],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(['name' => $testimonial['name']], $testimonial);
        }
    }
}
