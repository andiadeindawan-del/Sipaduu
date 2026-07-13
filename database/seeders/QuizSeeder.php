<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================================
        // AMBIL ID DARI DATABASE
        // ============================================================
        $trainingIds = DB::table('trainings')->pluck('id')->toArray();
        $materiIds = DB::table('materis')->pluck('id')->toArray();
        $userIds = DB::table('users')->where('role', 'trainer')->orWhere('role', 'admin')->pluck('id')->toArray();

        // Jika data tidak mencukupi, beri peringatan
        if (empty($trainingIds)) {
            $this->command->warn('⚠️ Tidak ada data training! Pastikan TrainingSeeder sudah dijalankan.');
            return;
        }

        if (empty($userIds)) {
            $this->command->warn('⚠️ Tidak ada data user! Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        // ============================================================
        // DATA QUIZZES (10 Data)
        // ============================================================
        $quizzes = [
            // Quiz 1 - Pelatihan Web Development dengan Laravel
            [
                'training_id' => $trainingIds[0] ?? 1,
                'materi_id' => $materiIds[0] ?? null,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Quiz 1: Pengenalan Laravel',
                'deskripsi' => 'Quiz untuk menguji pemahaman dasar tentang framework Laravel.',
                'durasi' => 30,
                'passing_score' => 70.00,
                'max_attempt' => 2,
                'is_random' => true,
                'show_result' => true,
                'status' => 'published',
                'order' => 1,
                'start_date' => '2026-08-01 08:00:00',
                'end_date' => '2026-08-05 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 2 - Pelatihan Web Development dengan Laravel
            [
                'training_id' => $trainingIds[0] ?? 1,
                'materi_id' => $materiIds[1] ?? null,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Quiz 2: Laravel Database & Eloquent',
                'deskripsi' => 'Quiz tentang penggunaan Eloquent ORM dan database di Laravel.',
                'durasi' => 45,
                'passing_score' => 75.00,
                'max_attempt' => 2,
                'is_random' => true,
                'show_result' => true,
                'status' => 'published',
                'order' => 2,
                'start_date' => '2026-08-02 08:00:00',
                'end_date' => '2026-08-05 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 3 - Pelatihan UI/UX Design untuk Pemula
            [
                'training_id' => $trainingIds[1] ?? 2,
                'materi_id' => $materiIds[2] ?? null,
                'created_by' => $userIds[1] ?? 3,
                'judul' => 'Quiz 1: Dasar-dasar UI/UX Design',
                'deskripsi' => 'Quiz tentang prinsip dasar desain UI/UX dan penggunaannya.',
                'durasi' => 25,
                'passing_score' => 70.00,
                'max_attempt' => 1,
                'is_random' => false,
                'show_result' => true,
                'status' => 'published',
                'order' => 1,
                'start_date' => '2026-08-10 08:00:00',
                'end_date' => '2026-08-12 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 4 - Strategi Digital Marketing untuk UMKM
            [
                'training_id' => $trainingIds[2] ?? 3,
                'materi_id' => $materiIds[3] ?? null,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Quiz 1: Digital Marketing Basics',
                'deskripsi' => 'Quiz tentang dasar-dasar digital marketing untuk UMKM.',
                'durasi' => 30,
                'passing_score' => 70.00,
                'max_attempt' => 2,
                'is_random' => true,
                'show_result' => true,
                'status' => 'published',
                'order' => 1,
                'start_date' => '2026-08-15 08:00:00',
                'end_date' => '2026-08-17 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 5 - Pelatihan E-Commerce dan Marketplace
            [
                'training_id' => $trainingIds[3] ?? 4,
                'materi_id' => $materiIds[4] ?? null,
                'created_by' => $userIds[2] ?? 4,
                'judul' => 'Quiz 1: E-Commerce Fundamentals',
                'deskripsi' => 'Quiz tentang konsep dasar e-commerce dan strategi penjualan online.',
                'durasi' => 35,
                'passing_score' => 75.00,
                'max_attempt' => 1,
                'is_random' => false,
                'show_result' => true,
                'status' => 'published',
                'order' => 1,
                'start_date' => '2026-09-01 08:00:00',
                'end_date' => '2026-09-03 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 6 - Manajemen SDM di Era Digital
            [
                'training_id' => $trainingIds[4] ?? 5,
                'materi_id' => $materiIds[5] ?? null,
                'created_by' => $userIds[1] ?? 3,
                'judul' => 'Quiz 1: Digital HR Management',
                'deskripsi' => 'Quiz tentang pengelolaan SDM di era digital.',
                'durasi' => 30,
                'passing_score' => 70.00,
                'max_attempt' => 2,
                'is_random' => true,
                'show_result' => true,
                'status' => 'published',
                'order' => 1,
                'start_date' => '2026-09-10 08:00:00',
                'end_date' => '2026-09-12 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 7 - Pelatihan Akuntansi Dasar
            [
                'training_id' => $trainingIds[5] ?? 6,
                'materi_id' => $materiIds[6] ?? null,
                'created_by' => $userIds[3] ?? 5,
                'judul' => 'Quiz 1: Dasar-dasar Akuntansi',
                'deskripsi' => 'Quiz tentang prinsip dasar akuntansi untuk pengusaha.',
                'durasi' => 30,
                'passing_score' => 70.00,
                'max_attempt' => 1,
                'is_random' => false,
                'show_result' => true,
                'status' => 'published',
                'order' => 1,
                'start_date' => '2026-09-20 08:00:00',
                'end_date' => '2026-09-22 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 8 - Branding Strategy untuk UMKM
            [
                'training_id' => $trainingIds[6] ?? 7,
                'materi_id' => $materiIds[7] ?? null,
                'created_by' => $userIds[2] ?? 4,
                'judul' => 'Quiz 1: Branding Strategy',
                'deskripsi' => 'Quiz tentang strategi branding untuk UMKM.',
                'durasi' => 25,
                'passing_score' => 70.00,
                'max_attempt' => 2,
                'is_random' => true,
                'show_result' => true,
                'status' => 'draft',
                'order' => 1,
                'start_date' => '2026-10-01 08:00:00',
                'end_date' => '2026-10-03 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 9 - Pelatihan Kewirausahaan
            [
                'training_id' => $trainingIds[7] ?? 8,
                'materi_id' => $materiIds[8] ?? null,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Quiz 1: Kewirausahaan & Inovasi',
                'deskripsi' => 'Quiz tentang kewirausahaan dan inovasi bisnis.',
                'durasi' => 40,
                'passing_score' => 75.00,
                'max_attempt' => 1,
                'is_random' => false,
                'show_result' => true,
                'status' => 'published',
                'order' => 1,
                'start_date' => '2026-10-10 08:00:00',
                'end_date' => '2026-10-12 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Quiz 10 - Manajemen Koperasi Modern
            [
                'training_id' => $trainingIds[8] ?? 9,
                'materi_id' => $materiIds[9] ?? null,
                'created_by' => $userIds[3] ?? 5,
                'judul' => 'Quiz 1: Manajemen Koperasi',
                'deskripsi' => 'Quiz tentang pengelolaan koperasi yang profesional.',
                'durasi' => 30,
                'passing_score' => 70.00,
                'max_attempt' => 2,
                'is_random' => true,
                'show_result' => true,
                'status' => 'published',
                'order' => 1,
                'start_date' => '2026-10-20 08:00:00',
                'end_date' => '2026-10-22 23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('quizzes')->insert($quizzes);

        // ============================================================
        // LOGGING
        // ============================================================
        $this->command->info('✅ Quiz seeder berhasil dijalankan!');
        $this->command->info('📝 Total quiz: ' . count($quizzes));
    }
}