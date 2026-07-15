<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================================
        // AMBIL ID QUIZ DARI DATABASE
        // ============================================================
        $quizIds = DB::table('quizzes')->pluck('id')->toArray();

        if (empty($quizIds)) {
            $this->command->warn('⚠️ Tidak ada data quiz! Pastikan QuizSeeder sudah dijalankan.');
            return;
        }

        // ============================================================
        // DATA QUIZ QUESTIONS (10 Data)
        // ============================================================
        $questions = [
            // ============================================================
            // QUIZ 1: Pelatihan Web Development dengan Laravel
            // ============================================================
            // Soal 1 - Multiple Choice
            [
                'quiz_id' => $quizIds[0] ?? 1,
                'question' => 'Apa yang dimaksud dengan framework Laravel?',
                'pertanyaan' => 'Apa yang dimaksud dengan framework Laravel?',
                'type' => 'multiple_choice',
                'tipe_soal' => 'pilihan',
                'score' => 10,
                'nilai' => 10,
                'options' => json_encode([
                    'Framework PHP untuk pengembangan web',
                    'Bahasa pemrograman baru',
                    'Database management system',
                    'Server hosting'
                ]),
                'opsi_a' => 'Framework PHP untuk pengembangan web',
                'opsi_b' => 'Bahasa pemrograman baru',
                'opsi_c' => 'Database management system',
                'opsi_d' => 'Server hosting',
                'opsi_e' => null,
                'correct_answer' => 'A',
                'jawaban_benar' => 'A',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Soal 2 - Multiple Choice
            [
                'quiz_id' => $quizIds[0] ?? 1,
                'question' => 'Apa nama arsitektur yang digunakan Laravel?',
                'pertanyaan' => 'Apa nama arsitektur yang digunakan Laravel?',
                'type' => 'multiple_choice',
                'tipe_soal' => 'pilihan',
                'score' => 10,
                'nilai' => 10,
                'options' => json_encode([
                    'MVC (Model-View-Controller)',
                    'MVVM (Model-View-ViewModel)',
                    'MVP (Model-View-Presenter)',
                    'REST API'
                ]),
                'opsi_a' => 'MVC (Model-View-Controller)',
                'opsi_b' => 'MVVM (Model-View-ViewModel)',
                'opsi_c' => 'MVP (Model-View-Presenter)',
                'opsi_d' => 'REST API',
                'opsi_e' => null,
                'correct_answer' => 'A',
                'jawaban_benar' => 'A',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Soal 3 - Essay
            [
                'quiz_id' => $quizIds[0] ?? 1,
                'question' => 'Jelaskan keunggulan menggunakan Laravel dibandingkan framework PHP lainnya!',
                'pertanyaan' => 'Jelaskan keunggulan menggunakan Laravel dibandingkan framework PHP lainnya!',
                'type' => 'essay',
                'tipe_soal' => 'essay',
                'score' => 20,
                'nilai' => 20,
                'options' => null,
                'opsi_a' => null,
                'opsi_b' => null,
                'opsi_c' => null,
                'opsi_d' => null,
                'opsi_e' => null,
                'correct_answer' => 'Laravel memiliki ekosistem yang lengkap, dokumentasi yang baik, dan fitur-fitur modern seperti Eloquent ORM, Blade templating, dan artisan CLI.',
                'jawaban_benar' => 'Laravel memiliki ekosistem yang lengkap, dokumentasi yang baik, dan fitur-fitur modern seperti Eloquent ORM, Blade templating, dan artisan CLI.',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============================================================
            // QUIZ 2: Pelatihan UI/UX Design untuk Pemula
            // ============================================================
            // Soal 4 - Multiple Choice
            [
                'quiz_id' => $quizIds[1] ?? 2,
                'question' => 'Apa kepanjangan dari UI?',
                'pertanyaan' => 'Apa kepanjangan dari UI?',
                'type' => 'multiple_choice',
                'tipe_soal' => 'pilihan',
                'score' => 10,
                'nilai' => 10,
                'options' => json_encode([
                    'User Interface',
                    'User Interaction',
                    'Universal Interface',
                    'Unique Interface'
                ]),
                'opsi_a' => 'User Interface',
                'opsi_b' => 'User Interaction',
                'opsi_c' => 'Universal Interface',
                'opsi_d' => 'Unique Interface',
                'opsi_e' => null,
                'correct_answer' => 'A',
                'jawaban_benar' => 'A',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Soal 5 - Multiple Choice
            [
                'quiz_id' => $quizIds[1] ?? 2,
                'question' => 'Apa kepanjangan dari UX?',
                'pertanyaan' => 'Apa kepanjangan dari UX?',
                'type' => 'multiple_choice',
                'tipe_soal' => 'pilihan',
                'score' => 10,
                'nilai' => 10,
                'options' => json_encode([
                    'User Experience',
                    'User Exchange',
                    'Universal Experience',
                    'Unique Experience'
                ]),
                'opsi_a' => 'User Experience',
                'opsi_b' => 'User Exchange',
                'opsi_c' => 'Universal Experience',
                'opsi_d' => 'Unique Experience',
                'opsi_e' => null,
                'correct_answer' => 'A',
                'jawaban_benar' => 'A',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Soal 6 - Essay
            [
                'quiz_id' => $quizIds[1] ?? 2,
                'question' => 'Jelaskan perbedaan antara UI dan UX!',
                'pertanyaan' => 'Jelaskan perbedaan antara UI dan UX!',
                'type' => 'essay',
                'tipe_soal' => 'essay',
                'score' => 20,
                'nilai' => 20,
                'options' => null,
                'opsi_a' => null,
                'opsi_b' => null,
                'opsi_c' => null,
                'opsi_d' => null,
                'opsi_e' => null,
                'correct_answer' => 'UI (User Interface) berfokus pada tampilan visual dan interaksi, sedangkan UX (User Experience) berfokus pada pengalaman keseluruhan pengguna.',
                'jawaban_benar' => 'UI (User Interface) berfokus pada tampilan visual dan interaksi, sedangkan UX (User Experience) berfokus pada pengalaman keseluruhan pengguna.',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============================================================
            // QUIZ 3: Strategi Digital Marketing untuk UMKM
            // ============================================================
            // Soal 7 - Multiple Choice
            [
                'quiz_id' => $quizIds[2] ?? 3,
                'question' => 'Apa kepanjangan dari SEO?',
                'pertanyaan' => 'Apa kepanjangan dari SEO?',
                'type' => 'multiple_choice',
                'tipe_soal' => 'pilihan',
                'score' => 10,
                'nilai' => 10,
                'options' => json_encode([
                    'Search Engine Optimization',
                    'Social Engine Optimization',
                    'Search Engine Operation',
                    'System Engine Optimization'
                ]),
                'opsi_a' => 'Search Engine Optimization',
                'opsi_b' => 'Social Engine Optimization',
                'opsi_c' => 'Search Engine Operation',
                'opsi_d' => 'System Engine Optimization',
                'opsi_e' => null,
                'correct_answer' => 'A',
                'jawaban_benar' => 'A',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Soal 8 - Multiple Choice
            [
                'quiz_id' => $quizIds[2] ?? 3,
                'question' => 'Apa yang dimaksud dengan SEM?',
                'pertanyaan' => 'Apa yang dimaksud dengan SEM?',
                'type' => 'multiple_choice',
                'tipe_soal' => 'pilihan',
                'score' => 10,
                'nilai' => 10,
                'options' => json_encode([
                    'Search Engine Marketing',
                    'Social Engine Marketing',
                    'Search Engine Management',
                    'System Engine Marketing'
                ]),
                'opsi_a' => 'Search Engine Marketing',
                'opsi_b' => 'Social Engine Marketing',
                'opsi_c' => 'Search Engine Management',
                'opsi_d' => 'System Engine Marketing',
                'opsi_e' => null,
                'correct_answer' => 'A',
                'jawaban_benar' => 'A',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Soal 9 - Essay
            [
                'quiz_id' => $quizIds[2] ?? 3,
                'question' => 'Jelaskan strategi digital marketing yang efektif untuk UMKM!',
                'pertanyaan' => 'Jelaskan strategi digital marketing yang efektif untuk UMKM!',
                'type' => 'essay',
                'tipe_soal' => 'essay',
                'score' => 20,
                'nilai' => 20,
                'options' => null,
                'opsi_a' => null,
                'opsi_b' => null,
                'opsi_c' => null,
                'opsi_d' => null,
                'opsi_e' => null,
                'correct_answer' => 'Strategi digital marketing efektif untuk UMKM meliputi: optimasi SEO, konten marketing, social media marketing, email marketing, dan iklan berbayar (PPC) dengan budget yang disesuaikan.',
                'jawaban_benar' => 'Strategi digital marketing efektif untuk UMKM meliputi: optimasi SEO, konten marketing, social media marketing, email marketing, dan iklan berbayar (PPC) dengan budget yang disesuaikan.',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============================================================
            // QUIZ 4: Pelatihan E-Commerce dan Marketplace
            // ============================================================
            // Soal 10 - Multiple Choice
            [
                'quiz_id' => $quizIds[3] ?? 4,
                'question' => 'Apa yang dimaksud dengan e-commerce?',
                'pertanyaan' => 'Apa yang dimaksud dengan e-commerce?',
                'type' => 'multiple_choice',
                'tipe_soal' => 'pilihan',
                'score' => 10,
                'nilai' => 10,
                'options' => json_encode([
                    'Transaksi jual beli secara online',
                    'Transaksi jual beli secara offline',
                    'Sistem manajemen inventaris',
                    'Platform media sosial'
                ]),
                'opsi_a' => 'Transaksi jual beli secara online',
                'opsi_b' => 'Transaksi jual beli secara offline',
                'opsi_c' => 'Sistem manajemen inventaris',
                'opsi_d' => 'Platform media sosial',
                'opsi_e' => null,
                'correct_answer' => 'A',
                'jawaban_benar' => 'A',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('quiz_questions')->insert($questions);

        // ============================================================
        // LOGGING
        // ============================================================
        $this->command->info('✅ Quiz Questions seeder berhasil dijalankan!');
        $this->command->info('📝 Total pertanyaan: ' . count($questions));
    }
}