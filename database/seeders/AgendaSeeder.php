<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgendaSeeder extends Seeder
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
        $userIds = DB::table('users')->whereIn('role', ['admin', 'trainer'])->pluck('id')->toArray();

        if (empty($trainingIds)) {
            $this->command->warn('⚠️ Tidak ada data training! Pastikan TrainingSeeder sudah dijalankan.');
            return;
        }

        if (empty($userIds)) {
            $this->command->warn('⚠️ Tidak ada data user! Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        // ============================================================
        // DATA AGENDAS (10 Data)
        // ============================================================
        $agendas = [
            // Agenda 1 - Pelatihan Web Development dengan Laravel
            [
                'training_id' => $trainingIds[0] ?? 1,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Pembukaan Pelatihan Web Development',
                'deskripsi' => 'Sesi pembukaan pelatihan web development. Pengenalan materi, jadwal, dan instruktur.',
                'tanggal' => '2026-08-01',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '09:30:00',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/abc-defg-hij',
                'tipe' => 'online',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 2 - Pelatihan Web Development dengan Laravel
            [
                'training_id' => $trainingIds[0] ?? 1,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Workshop Laravel Routing & Controller',
                'deskripsi' => 'Workshop praktik membuat routing dan controller di Laravel.',
                'tanggal' => '2026-08-02',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '12:00:00',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/abc-defg-hij',
                'tipe' => 'online',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 3 - Pelatihan Web Development dengan Laravel
            [
                'training_id' => $trainingIds[0] ?? 1,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Konsultasi Proyek Laravel',
                'deskripsi' => 'Sesi konsultasi untuk proyek akhir pelatihan Laravel.',
                'tanggal' => '2026-08-04',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:30:00',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/abc-defg-hij',
                'tipe' => 'online',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 4 - Pelatihan UI/UX Design
            [
                'training_id' => $trainingIds[1] ?? 2,
                'created_by' => $userIds[1] ?? 3,
                'judul' => 'Sesi 1: Prinsip Dasar UI/UX',
                'deskripsi' => 'Pengenalan prinsip dasar desain UI/UX dan studi kasus.',
                'tanggal' => '2026-08-10',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/klm-nop-qrs',
                'tipe' => 'online',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 5 - Pelatihan UI/UX Design
            [
                'training_id' => $trainingIds[1] ?? 2,
                'created_by' => $userIds[1] ?? 3,
                'judul' => 'Sesi 2: Praktik Design Figma',
                'deskripsi' => 'Praktik langsung membuat desain UI menggunakan Figma.',
                'tanggal' => '2026-08-11',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '12:00:00',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/klm-nop-qrs',
                'tipe' => 'online',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 6 - Strategi Digital Marketing
            [
                'training_id' => $trainingIds[2] ?? 3,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Pembukaan Digital Marketing Training',
                'deskripsi' => 'Sesi pembukaan pelatihan digital marketing untuk UMKM.',
                'tanggal' => '2026-08-15',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '09:00:00',
                'lokasi' => 'Gedung Dinas Koperindag Lantai 3, Mamuju',
                'link_meeting' => null,
                'tipe' => 'offline',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 7 - Strategi Digital Marketing
            [
                'training_id' => $trainingIds[2] ?? 3,
                'created_by' => $userIds[0] ?? 2,
                'judul' => 'Workshop SEO & Content Marketing',
                'deskripsi' => 'Workshop praktik SEO dan content marketing untuk UMKM.',
                'tanggal' => '2026-08-16',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '12:00:00',
                'lokasi' => 'Gedung Dinas Koperindag Lantai 3, Mamuju',
                'link_meeting' => null,
                'tipe' => 'offline',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 8 - Pelatihan E-Commerce
            [
                'training_id' => $trainingIds[3] ?? 4,
                'created_by' => $userIds[2] ?? 4,
                'judul' => 'Sesi 1: Pengenalan E-Commerce',
                'deskripsi' => 'Pengenalan dasar e-commerce dan platform yang digunakan.',
                'tanggal' => '2026-09-01',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/tuv-wxy-zab',
                'tipe' => 'online',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 9 - Pelatihan E-Commerce
            [
                'training_id' => $trainingIds[3] ?? 4,
                'created_by' => $userIds[2] ?? 4,
                'judul' => 'Sesi 2: Marketplace Strategy',
                'deskripsi' => 'Strategi berjualan di marketplace Shopee, Tokopedia, dan Lazada.',
                'tanggal' => '2026-09-02',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:30:00',
                'lokasi' => null,
                'link_meeting' => 'https://meet.google.com/tuv-wxy-zab',
                'tipe' => 'online',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agenda 10 - Manajemen Koperasi Modern
            [
                'training_id' => $trainingIds[8] ?? 9,
                'created_by' => $userIds[3] ?? 5,
                'judul' => 'Sesi 1: Pengelolaan Koperasi Modern',
                'deskripsi' => 'Pengenalan tata kelola koperasi yang profesional dan modern.',
                'tanggal' => '2026-10-20',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:30:00',
                'lokasi' => 'Gedung Dinas Koperindag Lantai 3, Mamuju',
                'link_meeting' => 'https://meet.google.com/uvw-xyz-abc',
                'tipe' => 'hybrid',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('agendas')->insert($agendas);

        // ============================================================
        // LOGGING
        // ============================================================
        $this->command->info('✅ Agenda seeder berhasil dijalankan!');
        $this->command->info('📅 Total agenda: ' . count($agendas));
    }
}