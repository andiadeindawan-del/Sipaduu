<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================================
        // AMBIL ID DARI DATABASE
        // ============================================================
        // Ambil ID trainer dari tabel users (role = trainer)
        $trainerIds = DB::table('users')->where('role', 'trainer')->pluck('id')->toArray();
        
        // Ambil ID peserta dari tabel users (role = peserta)
        $participantIds = DB::table('users')->where('role', 'peserta')->pluck('id')->toArray();
        
        // Ambil ID kategori dari tabel kategoris
        $kategoriIds = DB::table('kategoris')->pluck('id')->toArray();

        // Jika data tidak mencukupi, beri peringatan
        if (count($trainerIds) < 5) {
            $this->command->warn('⚠️ Data trainer kurang dari 5! Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        if (count($participantIds) < 5) {
            $this->command->warn('⚠️ Data peserta kurang dari 5! Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        // ============================================================
        // DATA TRAININGS (10 Data)
        // ============================================================
        $trainings = [
            [
                'kategori_id' => $kategoriIds[0] ?? 1,
                'trainer_id' => $trainerIds[0] ?? 2,
                'judul' => 'Pelatihan Web Development dengan Laravel',
                'deskripsi' => 'Pelatihan intensif pengembangan aplikasi web menggunakan framework Laravel 10. Cocok untuk pemula hingga menengah.',
                'tipe' => 'online',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/abc-defg-hij',
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2026-08-05',
                'kapasitas' => 30,
                'status' => 'published',
                'gambar' => 'trainings/laravel-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[0] ?? 1,
                'trainer_id' => $trainerIds[1] ?? 3,
                'judul' => 'Pelatihan UI/UX Design untuk Pemula',
                'deskripsi' => 'Pelajari dasar-dasar desain UI/UX menggunakan Figma dan Adobe XD. Praktik langsung dengan studi kasus.',
                'tipe' => 'online',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/klm-nop-qrs',
                'tanggal_mulai' => '2026-08-10',
                'tanggal_selesai' => '2026-08-12',
                'kapasitas' => 25,
                'status' => 'published',
                'gambar' => 'trainings/uiux-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[1] ?? 2,
                'trainer_id' => $trainerIds[0] ?? 2,
                'judul' => 'Strategi Digital Marketing untuk UMKM',
                'deskripsi' => 'Pelatihan strategi pemasaran digital yang efektif untuk usaha mikro, kecil, dan menengah. Meliputi SEO, SEM, dan Social Media Marketing.',
                'tipe' => 'offline',
                'lokasi' => 'Gedung Dinas Koperindag Lantai 3, Mamuju',
                'link_meeting' => null,
                'tanggal_mulai' => '2026-08-15',
                'tanggal_selesai' => '2026-08-17',
                'kapasitas' => 40,
                'status' => 'berjalan',
                'gambar' => 'trainings/digital-marketing.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[1] ?? 2,
                'trainer_id' => $trainerIds[2] ?? 4,
                'judul' => 'Pelatihan E-Commerce dan Marketplace',
                'deskripsi' => 'Pelatihan tentang cara membangun dan mengelola toko online di berbagai marketplace seperti Shopee, Tokopedia, dan Lazada.',
                'tipe' => 'online',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/tuv-wxy-zab',
                'tanggal_mulai' => '2026-09-01',
                'tanggal_selesai' => '2026-09-03',
                'kapasitas' => 35,
                'status' => 'published',
                'gambar' => 'trainings/ecommerce-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[2] ?? 3,
                'trainer_id' => $trainerIds[1] ?? 3,
                'judul' => 'Manajemen SDM di Era Digital',
                'deskripsi' => 'Pelatihan tentang pengelolaan sumber daya manusia dengan memanfaatkan teknologi digital. Termasuk rekrutmen online dan performance management.',
                'tipe' => 'hybrid',
                'lokasi' => 'Gedung Dinas Koperindag Lantai 3, Mamuju',
                'link_meeting' => 'https://meet.google.com/cde-fgh-ijk',
                'tanggal_mulai' => '2026-09-10',
                'tanggal_selesai' => '2026-09-12',
                'kapasitas' => 30,
                'status' => 'published',
                'gambar' => 'trainings/hrm-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[3] ?? 4,
                'trainer_id' => $trainerIds[3] ?? 5,
                'judul' => 'Pelatihan Akuntansi Dasar untuk Pengusaha',
                'deskripsi' => 'Pelatihan akuntansi dasar yang mudah dipahami untuk pengusaha dan pelaku UMKM. Termasuk pembuatan laporan keuangan sederhana.',
                'tipe' => 'offline',
                'lokasi' => 'Gedung Dinas Koperindag Lantai 3, Mamuju',
                'link_meeting' => null,
                'tanggal_mulai' => '2026-09-20',
                'tanggal_selesai' => '2026-09-22',
                'kapasitas' => 25,
                'status' => 'published',
                'gambar' => 'trainings/accounting-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[4] ?? 5,
                'trainer_id' => $trainerIds[2] ?? 4,
                'judul' => 'Branding Strategy untuk UMKM',
                'deskripsi' => 'Pelatihan tentang cara membangun merek yang kuat untuk usaha kecil dan menengah. Meliputi brand identity, storytelling, dan brand positioning.',
                'tipe' => 'online',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/lmn-opq-rst',
                'tanggal_mulai' => '2026-10-01',
                'tanggal_selesai' => '2026-10-03',
                'kapasitas' => 30,
                'status' => 'draft',
                'gambar' => 'trainings/branding-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[5] ?? 6,
                'trainer_id' => $trainerIds[0] ?? 2,
                'judul' => 'Pelatihan Kewirausahaan dan Inovasi Bisnis',
                'deskripsi' => 'Pelatihan tentang cara memulai dan mengembangkan bisnis dengan inovasi. Meliputi business model canvas, customer validation, dan pitching.',
                'tipe' => 'offline',
                'lokasi' => 'Gedung Dinas Koperindag Lantai 3, Mamuju',
                'link_meeting' => null,
                'tanggal_mulai' => '2026-10-10',
                'tanggal_selesai' => '2026-10-12',
                'kapasitas' => 35,
                'status' => 'selesai',
                'gambar' => 'trainings/entrepreneurship-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[7] ?? 8,
                'trainer_id' => $trainerIds[3] ?? 5,
                'judul' => 'Manajemen Koperasi Modern',
                'deskripsi' => 'Pelatihan tentang pengelolaan koperasi yang profesional dan modern. Termasuk tata kelola, keuangan, dan pengembangan anggota.',
                'tipe' => 'hybrid',
                'lokasi' => 'Gedung Dinas Koperindag Lantai 3, Mamuju',
                'link_meeting' => 'https://meet.google.com/uvw-xyz-abc',
                'tanggal_mulai' => '2026-10-20',
                'tanggal_selesai' => '2026-10-22',
                'kapasitas' => 30,
                'status' => 'published',
                'gambar' => 'trainings/cooperative-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => $kategoriIds[9] ?? 10,
                'trainer_id' => $trainerIds[1] ?? 3,
                'judul' => 'Pelatihan Kepemimpinan untuk Manajer Muda',
                'deskripsi' => 'Pelatihan tentang keterampilan kepemimpinan yang efektif untuk manajer dan calon pemimpin. Termasuk komunikasi, decision making, dan team building.',
                'tipe' => 'online',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/def-ghi-jkl',
                'tanggal_mulai' => '2026-11-01',
                'tanggal_selesai' => '2026-11-03',
                'kapasitas' => 40,
                'status' => 'draft',
                'gambar' => 'trainings/leadership-training.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('trainings')->insert($trainings);

        // ============================================================
        // DATA TRAINING PARTICIPANTS
        // ============================================================
        $participants = [
            // Training ID 1
            [
                'training_id' => 1,
                'user_id' => $participantIds[0] ?? 6,
                'status' => 'completed',
                'registered_at' => '2026-07-25 08:00:00',
                'completed_at' => '2026-08-05 17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 1,
                'user_id' => $participantIds[1] ?? 7,
                'status' => 'completed',
                'registered_at' => '2026-07-26 09:00:00',
                'completed_at' => '2026-08-05 17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 1,
                'user_id' => $participantIds[2] ?? 8,
                'status' => 'registered',
                'registered_at' => '2026-07-28 10:00:00',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Training ID 2
            [
                'training_id' => 2,
                'user_id' => $participantIds[0] ?? 6,
                'status' => 'registered',
                'registered_at' => '2026-08-05 08:00:00',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 2,
                'user_id' => $participantIds[3] ?? 9,
                'status' => 'registered',
                'registered_at' => '2026-08-06 09:00:00',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Training ID 3
            [
                'training_id' => 3,
                'user_id' => $participantIds[1] ?? 7,
                'status' => 'attended',
                'registered_at' => '2026-08-10 08:00:00',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 3,
                'user_id' => $participantIds[4] ?? 10,
                'status' => 'attended',
                'registered_at' => '2026-08-11 09:00:00',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Training ID 4
            [
                'training_id' => 4,
                'user_id' => $participantIds[0] ?? 6,
                'status' => 'registered',
                'registered_at' => '2026-08-25 08:00:00',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Training ID 5
            [
                'training_id' => 5,
                'user_id' => $participantIds[2] ?? 8,
                'status' => 'registered',
                'registered_at' => '2026-09-05 08:00:00',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Training ID 8
            [
                'training_id' => 8,
                'user_id' => $participantIds[1] ?? 7,
                'status' => 'completed',
                'registered_at' => '2026-10-05 08:00:00',
                'completed_at' => '2026-10-12 17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('training_participants')->insert($participants);

        // ============================================================
        // LOGGING
        // ============================================================
        $this->command->info('✅ Training seeder berhasil dijalankan!');
        $this->command->info('📚 Total training: ' . count($trainings));
        $this->command->info('👥 Total peserta: ' . count($participants));
    }
}