<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================================
        // AMBIL ID DARI DATABASE
        // ============================================================
        $kategoriIds = DB::table('kategoris')->pluck('id')->toArray();
        $trainingIds = DB::table('trainings')->pluck('id')->toArray();
        $userIds = DB::table('users')->where('role', 'peserta')->pluck('id')->toArray();

        if (empty($trainingIds)) {
            $this->command->warn('⚠️ Tidak ada data training! Pastikan TrainingSeeder sudah dijalankan.');
            return;
        }

        if (empty($userIds)) {
            $this->command->warn('⚠️ Tidak ada data user! Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        // ============================================================
        // DATA MATERIS (10 Data)
        // ============================================================
        $materis = [
            // Materi 1 - Pelatihan Web Development dengan Laravel
            [
                'kategori_id' => $kategoriIds[0] ?? 1,
                'training_id' => $trainingIds[0] ?? 1,
                'judul' => 'Pengenalan Laravel Framework',
                'slug' => Str::slug('Pengenalan Laravel Framework'),
                'deskripsi' => 'Pengenalan dasar tentang framework Laravel, sejarah, keunggulan, dan arsitektur MVC.',
                'konten' => 'Laravel adalah framework PHP yang powerful untuk pengembangan web. Materi ini membahas instalasi, struktur folder, dan konsep dasar routing.',
                'file_data' => json_encode([
                    ['path' => 'materis/laravel-intro.pdf', 'url' => '/storage/materis/laravel-intro.pdf', 'type' => 'pdf', 'name' => 'Laravel_Introduction.pdf', 'size' => 2457600],
                    ['path' => 'materis/laravel-slides.pptx', 'url' => '/storage/materis/laravel-slides.pptx', 'type' => 'ppt', 'name' => 'Laravel_Presentation.pptx', 'size' => 5120000],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/laravel-intro.pdf',
                'file_path' => 'materis/laravel-intro.pdf',
                'durasi' => 60,
                'order' => 1,
                'is_free' => false,
                'total_files' => 2,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 2 - Pelatihan Web Development dengan Laravel
            [
                'kategori_id' => $kategoriIds[0] ?? 1,
                'training_id' => $trainingIds[0] ?? 1,
                'judul' => 'Routing dan Controller Laravel',
                'slug' => Str::slug('Routing dan Controller Laravel'),
                'deskripsi' => 'Pembahasan tentang routing, controller, dan middleware di Laravel.',
                'konten' => 'Routing adalah inti dari aplikasi web. Materi ini membahas cara membuat route, controller, dan middleware untuk mengatur alur aplikasi.',
                'file_data' => json_encode([
                    ['path' => 'materis/laravel-routing.pdf', 'url' => '/storage/materis/laravel-routing.pdf', 'type' => 'pdf', 'name' => 'Laravel_Routing.pdf', 'size' => 1894400],
                    ['path' => 'materis/routing-video.mp4', 'url' => '/storage/materis/routing-video.mp4', 'type' => 'video', 'name' => 'Routing_Tutorial.mp4', 'size' => 52428800],
                ]),
                'tipe_file' => 'video',
                'file_url' => '/storage/materis/routing-video.mp4',
                'file_path' => 'materis/routing-video.mp4',
                'durasi' => 90,
                'order' => 2,
                'is_free' => false,
                'total_files' => 2,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 3 - Pelatihan UI/UX Design
            [
                'kategori_id' => $kategoriIds[0] ?? 1,
                'training_id' => $trainingIds[1] ?? 2,
                'judul' => 'Prinsip Dasar UI/UX Design',
                'slug' => Str::slug('Prinsip Dasar UI UX Design'),
                'deskripsi' => 'Pengenalan prinsip-prinsip dasar desain UI/UX dan pentingnya user experience.',
                'konten' => 'UI/UX adalah kunci kesuksesan produk digital. Materi ini membahas prinsip desain, user research, dan usability testing.',
                'file_data' => json_encode([
                    ['path' => 'materis/uiux-principles.pdf', 'url' => '/storage/materis/uiux-principles.pdf', 'type' => 'pdf', 'name' => 'UIUX_Principles.pdf', 'size' => 3145728],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/uiux-principles.pdf',
                'file_path' => 'materis/uiux-principles.pdf',
                'durasi' => 45,
                'order' => 1,
                'is_free' => true,
                'total_files' => 1,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 4 - Strategi Digital Marketing
            [
                'kategori_id' => $kategoriIds[1] ?? 2,
                'training_id' => $trainingIds[2] ?? 3,
                'judul' => 'Pengenalan Digital Marketing',
                'slug' => Str::slug('Pengenalan Digital Marketing'),
                'deskripsi' => 'Pengenalan konsep digital marketing dan strategi untuk UMKM.',
                'konten' => 'Digital marketing adalah strategi pemasaran menggunakan media digital. Materi ini membahas SEO, SEM, dan social media marketing.',
                'file_data' => json_encode([
                    ['path' => 'materis/digital-marketing-basics.pdf', 'url' => '/storage/materis/digital-marketing-basics.pdf', 'type' => 'pdf', 'name' => 'Digital_Marketing_Basics.pdf', 'size' => 2097152],
                    ['path' => 'materis/seo-guide.pdf', 'url' => '/storage/materis/seo-guide.pdf', 'type' => 'pdf', 'name' => 'SEO_Guide.pdf', 'size' => 1572864],
                    ['path' => 'materis/social-media-strategy.pdf', 'url' => '/storage/materis/social-media-strategy.pdf', 'type' => 'pdf', 'name' => 'Social_Media_Strategy.pdf', 'size' => 1835008],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/digital-marketing-basics.pdf',
                'file_path' => 'materis/digital-marketing-basics.pdf',
                'durasi' => 75,
                'order' => 1,
                'is_free' => true,
                'total_files' => 3,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 5 - Pelatihan E-Commerce
            [
                'kategori_id' => $kategoriIds[1] ?? 2,
                'training_id' => $trainingIds[3] ?? 4,
                'judul' => 'Strategi Memulai E-Commerce',
                'slug' => Str::slug('Strategi Memulai E Commerce'),
                'deskripsi' => 'Panduan lengkap memulai bisnis e-commerce untuk pemula.',
                'konten' => 'E-commerce adalah bisnis jual beli online. Materi ini membahas platform e-commerce, strategi marketing, dan tips sukses berjualan online.',
                'file_data' => json_encode([
                    ['path' => 'materis/ecommerce-guide.pdf', 'url' => '/storage/materis/ecommerce-guide.pdf', 'type' => 'pdf', 'name' => 'Ecommerce_Guide.pdf', 'size' => 4194304],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/ecommerce-guide.pdf',
                'file_path' => 'materis/ecommerce-guide.pdf',
                'durasi' => 60,
                'order' => 1,
                'is_free' => false,
                'total_files' => 1,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 6 - Manajemen SDM
            [
                'kategori_id' => $kategoriIds[2] ?? 3,
                'training_id' => $trainingIds[4] ?? 5,
                'judul' => 'Digital HR Management',
                'slug' => Str::slug('Digital HR Management'),
                'deskripsi' => 'Pengelolaan sumber daya manusia di era digital.',
                'konten' => 'HR digital adalah transformasi pengelolaan SDM menggunakan teknologi. Materi ini membahas e-recruitment, performance management, dan HR analytics.',
                'file_data' => json_encode([
                    ['path' => 'materis/digital-hr.pdf', 'url' => '/storage/materis/digital-hr.pdf', 'type' => 'pdf', 'name' => 'Digital_HR.pdf', 'size' => 3670016],
                    ['path' => 'materis/hr-analytics.pptx', 'url' => '/storage/materis/hr-analytics.pptx', 'type' => 'ppt', 'name' => 'HR_Analytics.pptx', 'size' => 2831152],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/digital-hr.pdf',
                'file_path' => 'materis/digital-hr.pdf',
                'durasi' => 80,
                'order' => 1,
                'is_free' => false,
                'total_files' => 2,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 7 - Akuntansi Dasar
            [
                'kategori_id' => $kategoriIds[3] ?? 4,
                'training_id' => $trainingIds[5] ?? 6,
                'judul' => 'Pengenalan Akuntansi Dasar',
                'slug' => Str::slug('Pengenalan Akuntansi Dasar'),
                'deskripsi' => 'Dasar-dasar akuntansi untuk pengusaha dan pelaku UMKM.',
                'konten' => 'Akuntansi adalah bahasa bisnis. Materi ini membahas siklus akuntansi, jurnal, buku besar, dan laporan keuangan sederhana.',
                'file_data' => json_encode([
                    ['path' => 'materis/accounting-basics.pdf', 'url' => '/storage/materis/accounting-basics.pdf', 'type' => 'pdf', 'name' => 'Accounting_Basics.pdf', 'size' => 2621440],
                    ['path' => 'materis/accounting-examples.xlsx', 'url' => '/storage/materis/accounting-examples.xlsx', 'type' => 'other', 'name' => 'Accounting_Examples.xlsx', 'size' => 573440],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/accounting-basics.pdf',
                'file_path' => 'materis/accounting-basics.pdf',
                'durasi' => 60,
                'order' => 1,
                'is_free' => false,
                'total_files' => 2,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 8 - Branding Strategy
            [
                'kategori_id' => $kategoriIds[4] ?? 5,
                'training_id' => $trainingIds[6] ?? 7,
                'judul' => 'Branding untuk UMKM',
                'slug' => Str::slug('Branding untuk UMKM'),
                'deskripsi' => 'Strategi branding yang efektif untuk usaha kecil dan menengah.',
                'konten' => 'Branding adalah proses membangun identitas merek. Materi ini membahas brand identity, storytelling, dan brand positioning.',
                'file_data' => json_encode([
                    ['path' => 'materis/branding-strategy.pdf', 'url' => '/storage/materis/branding-strategy.pdf', 'type' => 'pdf', 'name' => 'Branding_Strategy.pdf', 'size' => 3211264],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/branding-strategy.pdf',
                'file_path' => 'materis/branding-strategy.pdf',
                'durasi' => 45,
                'order' => 1,
                'is_free' => true,
                'total_files' => 1,
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 9 - Kewirausahaan
            [
                'kategori_id' => $kategoriIds[5] ?? 6,
                'training_id' => $trainingIds[7] ?? 8,
                'judul' => 'Business Model Canvas',
                'slug' => Str::slug('Business Model Canvas'),
                'deskripsi' => 'Panduan membuat business model canvas untuk startup dan bisnis baru.',
                'konten' => 'Business Model Canvas adalah alat untuk merancang model bisnis. Materi ini membahas 9 blok BMC dan cara menggunakannya.',
                'file_data' => json_encode([
                    ['path' => 'materis/bmc-guide.pdf', 'url' => '/storage/materis/bmc-guide.pdf', 'type' => 'pdf', 'name' => 'BMC_Guide.pdf', 'size' => 2831152],
                    ['path' => 'materis/bmc-template.pptx', 'url' => '/storage/materis/bmc-template.pptx', 'type' => 'ppt', 'name' => 'BMC_Template.pptx', 'size' => 1048576],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/bmc-guide.pdf',
                'file_path' => 'materis/bmc-guide.pdf',
                'durasi' => 60,
                'order' => 1,
                'is_free' => false,
                'total_files' => 2,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Materi 10 - Manajemen Koperasi
            [
                'kategori_id' => $kategoriIds[7] ?? 8,
                'training_id' => $trainingIds[8] ?? 9,
                'judul' => 'Pengelolaan Koperasi Modern',
                'slug' => Str::slug('Pengelolaan Koperasi Modern'),
                'deskripsi' => 'Tata kelola dan pengelolaan koperasi yang profesional.',
                'konten' => 'Koperasi modern memerlukan tata kelola yang baik. Materi ini membahas prinsip koperasi, manajemen keuangan, dan pengembangan anggota.',
                'file_data' => json_encode([
                    ['path' => 'materis/cooperative-management.pdf', 'url' => '/storage/materis/cooperative-management.pdf', 'type' => 'pdf', 'name' => 'Cooperative_Management.pdf', 'size' => 3145728],
                    ['path' => 'materis/cooperative-case-study.pdf', 'url' => '/storage/materis/cooperative-case-study.pdf', 'type' => 'pdf', 'name' => 'Case_Study.pdf', 'size' => 1048576],
                ]),
                'tipe_file' => 'pdf',
                'file_url' => '/storage/materis/cooperative-management.pdf',
                'file_path' => 'materis/cooperative-management.pdf',
                'durasi' => 70,
                'order' => 1,
                'is_free' => false,
                'total_files' => 2,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('materis')->insert($materis);

        // ============================================================
        // DATA MATERI PROGRESS
        // ============================================================
        $materiIds = DB::table('materis')->pluck('id')->toArray();

        $progressData = [
            // User 1 (Budi Santoso)
            [
                'materi_id' => $materiIds[0] ?? 1,
                'user_id' => $userIds[0] ?? 6,
                'status' => 'completed',
                'progress' => 100,
                'completed_at' => '2026-08-05 15:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => $materiIds[1] ?? 2,
                'user_id' => $userIds[0] ?? 6,
                'status' => 'in_progress',
                'progress' => 70,
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => $materiIds[2] ?? 3,
                'user_id' => $userIds[0] ?? 6,
                'status' => 'not_started',
                'progress' => 0,
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // User 2 (Dewi Lestari)
            [
                'materi_id' => $materiIds[0] ?? 1,
                'user_id' => $userIds[1] ?? 7,
                'status' => 'completed',
                'progress' => 100,
                'completed_at' => '2026-08-04 14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => $materiIds[1] ?? 2,
                'user_id' => $userIds[1] ?? 7,
                'status' => 'completed',
                'progress' => 100,
                'completed_at' => '2026-08-06 16:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => $materiIds[2] ?? 3,
                'user_id' => $userIds[1] ?? 7,
                'status' => 'in_progress',
                'progress' => 50,
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => $materiIds[3] ?? 4,
                'user_id' => $userIds[1] ?? 7,
                'status' => 'not_started',
                'progress' => 0,
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // User 3 (Ahmad Subekti)
            [
                'materi_id' => $materiIds[0] ?? 1,
                'user_id' => $userIds[2] ?? 8,
                'status' => 'in_progress',
                'progress' => 45,
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => $materiIds[4] ?? 5,
                'user_id' => $userIds[2] ?? 8,
                'status' => 'not_started',
                'progress' => 0,
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // User 4 (Rina Marlina)
            [
                'materi_id' => $materiIds[5] ?? 6,
                'user_id' => $userIds[3] ?? 9,
                'status' => 'completed',
                'progress' => 100,
                'completed_at' => '2026-09-12 12:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => $materiIds[6] ?? 7,
                'user_id' => $userIds[3] ?? 9,
                'status' => 'in_progress',
                'progress' => 80,
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // User 5 (Joko Widodo)
            [
                'materi_id' => $materiIds[8] ?? 9,
                'user_id' => $userIds[4] ?? 10,
                'status' => 'completed',
                'progress' => 100,
                'completed_at' => '2026-10-12 17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('materi_progress')->insert($progressData);

        // ============================================================
        // LOGGING
        // ============================================================
        $this->command->info('✅ Materi seeder berhasil dijalankan!');
        $this->command->info('📚 Total materi: ' . count($materis));
        $this->command->info('📊 Total progress: ' . count($progressData));
    }
}