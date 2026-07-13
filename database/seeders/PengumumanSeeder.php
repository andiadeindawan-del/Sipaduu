<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengumumanSeeder extends Seeder
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
        $kategoriIds = DB::table('kategoris')->pluck('id')->toArray();
        $userIds = DB::table('users')->whereIn('role', ['admin', 'trainer'])->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('⚠️ Tidak ada data user! Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        // ============================================================
        // DATA PENGUMUMAN (10 Data)
        // ============================================================
        $pengumuman = [
            // Pengumuman 1 - Pinned (Semua Audience)
            [
                'training_id' => null,
                'kategori_id' => null,
                'created_by' => $userIds[0] ?? 2,
                'judul' => '🎉 Selamat Datang di SIPADU!',
                'deskripsi' => 'Sistem Pelatihan Digital untuk pengembangan SDM usaha KOPERINDAG.',
                'konten' => '<h3>Selamat Datang di SIPADU!</h3>
                            <p>Kami dengan senang hati memperkenalkan <strong>SIPADU</strong> (Sistem Pelatihan Digital) sebagai platform pembelajaran dan pengembangan SDM usaha di lingkungan Dinas Koperindag Provinsi Sulawesi Barat.</p>
                            <p>Melalui platform ini, Anda dapat:</p>
                            <ul>
                                <li>Mengikuti berbagai pelatihan berkualitas</li>
                                <li>Mengakses materi pembelajaran kapan saja</li>
                                <li>Mengerjakan quiz dan mendapatkan sertifikat</li>
                                <li>Berinteraksi dengan instruktur dan sesama peserta</li>
                            </ul>
                            <p>Mari bersama-sama tingkatkan kompetensi dan kualitas usaha kita!</p>
                            <p><strong>Salam sukses,</strong><br>Tim SIPADU</p>',
                'tanggal' => '2026-07-01',
                'tanggal_selesai' => null,
                'status' => 'published',
                'is_pinned' => true,
                'target_audience' => 'all',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 2 - Pelatihan Web Development
            [
                'training_id' => $trainingIds[0] ?? 1,
                'kategori_id' => $kategoriIds[0] ?? 1,
                'created_by' => $userIds[0] ?? 2,
                'judul' => '📢 Pengumuman Penting: Pelatihan Web Development',
                'deskripsi' => 'Informasi penting terkait pelatihan web development yang akan dimulai.',
                'konten' => '<h3>Pelatihan Web Development dengan Laravel</h3>
                            <p>Assalamualaikum warahmatullahi wabarakatuh,</p>
                            <p>Dengan ini kami informasikan bahwa pelatihan <strong>Web Development dengan Laravel</strong> akan dimulai pada:</p>
                            <ul>
                                <li><strong>Tanggal:</strong> 1-5 Agustus 2026</li>
                                <li><strong>Waktu:</strong> 08:00 - 17:00 WITA</li>
                                <li><strong>Tempat:</strong> Online via Google Meet</li>
                            </ul>
                            <p>Link meeting akan dikirimkan melalui email dan di grup WhatsApp.</p>
                            <p>Pastikan Anda sudah menyiapkan:</p>
                            <ul>
                                <li>Laptop/PC dengan spesifikasi minimal</li>
                                <li>Koneksi internet yang stabil</li>
                                <li>Aplikasi Laragon/XAMPP</li>
                                <li>Editor code (VS Code)</li>
                            </ul>
                            <p>Terima kasih.</p>',
                'tanggal' => '2026-07-25',
                'tanggal_selesai' => '2026-08-01',
                'status' => 'published',
                'is_pinned' => false,
                'target_audience' => 'peserta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 3 - Jadwal UI/UX
            [
                'training_id' => $trainingIds[1] ?? 2,
                'kategori_id' => $kategoriIds[0] ?? 1,
                'created_by' => $userIds[1] ?? 3,
                'judul' => '📅 Jadwal Pelatihan UI/UX Design',
                'deskripsi' => 'Jadwal lengkap pelatihan UI/UX Design untuk pemula.',
                'konten' => '<h3>Jadwal Pelatihan UI/UX Design</h3>
                            <p>Berikut adalah jadwal lengkap pelatihan UI/UX Design:</p>
                            <table border="1" cellpadding="5">
                                <tr>
                                    <th>Hari</th>
                                    <th>Tanggal</th>
                                    <th>Materi</th>
                                </tr>
                                <tr>
                                    <td>Senin</td>
                                    <td>10 Agustus 2026</td>
                                    <td>Prinsip Dasar UI/UX</td>
                                </tr>
                                <tr>
                                    <td>Selasa</td>
                                    <td>11 Agustus 2026</td>
                                    <td>Praktik Design Figma</td>
                                </tr>
                                <tr>
                                    <td>Rabu</td>
                                    <td>12 Agustus 2026</td>
                                    <td>Prototyping & Testing</td>
                                </tr>
                            </table>
                            <p>Link meeting akan dibagikan H-1 pelaksanaan.</p>',
                'tanggal' => '2026-08-05',
                'tanggal_selesai' => '2026-08-12',
                'status' => 'published',
                'is_pinned' => false,
                'target_audience' => 'peserta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 4 - Info UMKM
            [
                'training_id' => null,
                'kategori_id' => $kategoriIds[1] ?? 2,
                'created_by' => $userIds[0] ?? 2,
                'judul' => '💡 Tips Sukses Digital Marketing untuk UMKM',
                'deskripsi' => 'Tips dan trik sukses digital marketing untuk pelaku UMKM.',
                'konten' => '<h3>Tips Sukses Digital Marketing untuk UMKM</h3>
                            <p>Berikut beberapa tips sukses digital marketing untuk UMKM:</p>
                            <ol>
                                <li><strong>Kenali Target Audience Anda</strong> - Pahami siapa pelanggan Anda</li>
                                <li><strong>Gunakan Media Sosial</strong> - Manfaatkan Instagram, Facebook, TikTok</li>
                                <li><strong>Optimasi SEO</strong> - Buat website yang SEO friendly</li>
                                <li><strong>Content Marketing</strong> - Buat konten yang bermanfaat</li>
                                <li><strong>Email Marketing</strong> - Bangun database pelanggan</li>
                            </ol>
                            <p>Jangan lupa untuk selalu konsisten dan evaluasi strategi Anda!</p>',
                'tanggal' => '2026-08-10',
                'tanggal_selesai' => null,
                'status' => 'published',
                'is_pinned' => false,
                'target_audience' => 'peserta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 5 - Sertifikat
            [
                'training_id' => null,
                'kategori_id' => null,
                'created_by' => $userIds[0] ?? 2,
                'judul' => '🏆 Pengumuman Sertifikat Pelatihan',
                'deskripsi' => 'Informasi mengenai pengambilan sertifikat pelatihan.',
                'konten' => '<h3>Pengambilan Sertifikat Pelatihan</h3>
                            <p>Diumumkan kepada seluruh peserta yang telah menyelesaikan pelatihan, bahwa sertifikat dapat diambil pada:</p>
                            <ul>
                                <li><strong>Tanggal:</strong> 15-30 Agustus 2026</li>
                                <li><strong>Waktu:</strong> 09:00 - 16:00 WITA</li>
                                <li><strong>Tempat:</strong> Gedung Dinas Koperindag Lantai 3</li>
                            </ul>
                            <p>Persyaratan:</p>
                            <ul>
                                <li>Membawa fotokopi KTP</li>
                                <li>Menunjukkan bukti kehadiran pelatihan (minimal 80%)</li>
                                <li>Mengisi form verifikasi</li>
                            </ul>
                            <p>Untuk yang berhalangan hadir, dapat menghubungi admin untuk pengambilan via online.</p>',
                'tanggal' => '2026-08-15',
                'tanggal_selesai' => '2026-08-30',
                'status' => 'published',
                'is_pinned' => false,
                'target_audience' => 'all',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 6 - E-Commerce
            [
                'training_id' => $trainingIds[3] ?? 4,
                'kategori_id' => $kategoriIds[1] ?? 2,
                'created_by' => $userIds[2] ?? 4,
                'judul' => '🛒 Pelatihan E-Commerce Segera Dibuka',
                'deskripsi' => 'Pendaftaran pelatihan e-commerce dan marketplace dibuka.',
                'konten' => '<h3>Pendaftaran Pelatihan E-Commerce</h3>
                            <p>Kami dengan senang hati mengumumkan pembukaan pendaftaran pelatihan <strong>E-Commerce dan Marketplace</strong>.</p>
                            <p><strong>Detail Pelatihan:</strong></p>
                            <ul>
                                <li><strong>Tanggal:</strong> 1-3 September 2026</li>
                                <li><strong>Waktu:</strong> 08:00 - 17:00 WITA</li>
                                <li><strong>Metode:</strong> Online via Zoom</li>
                                <li><strong>Kuota:</strong> 35 peserta</li>
                            </ul>
                            <p><strong>Materi:</strong></p>
                            <ul>
                                <li>Pengenalan E-Commerce</li>
                                <li>Strategi Marketplace (Shopee, Tokopedia, Lazada)</li>
                                <li>Digital Marketing untuk E-Commerce</li>
                                <li>Manajemen Operasional Online</li>
                            </ul>
                            <p>Pendaftaran dibuka hingga 25 Agustus 2026.</p>
                            <p>Link pendaftaran: <a href="#">sipadu.koperindag.com/ecommerce</a></p>',
                'tanggal' => '2026-08-20',
                'tanggal_selesai' => '2026-09-03',
                'status' => 'published',
                'is_pinned' => true,
                'target_audience' => 'peserta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 7 - Libur Nasional
            [
                'training_id' => null,
                'kategori_id' => null,
                'created_by' => $userIds[0] ?? 2,
                'judul' => '📆 Libur Nasional 17 Agustus 2026',
                'deskripsi' => 'Informasi libur nasional dalam rangka Hari Kemerdekaan RI.',
                'konten' => '<h3>Libur Nasional 17 Agustus 2026</h3>
                            <p>Diberitahukan kepada seluruh peserta dan instruktur bahwa pada:</p>
                            <ul>
                                <li><strong>Tanggal:</strong> 17 Agustus 2026</li>
                                <li><strong>Keterangan:</strong> Libur Nasional (Hari Kemerdekaan RI)</li>
                            </ul>
                            <p>Seluruh kegiatan pelatihan pada tanggal tersebut <strong>DILIBURKAN</strong> dan akan dilanjutkan pada hari berikutnya.</p>
                            <p>Selamat merayakan Hari Kemerdekaan Republik Indonesia yang ke-79!</p>
                            <p><strong>Dirgahayu Indonesiaku! 🇮🇩</strong></p>',
                'tanggal' => '2026-08-17',
                'tanggal_selesai' => null,
                'status' => 'published',
                'is_pinned' => false,
                'target_audience' => 'all',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 8 - Koperasi
            [
                'training_id' => $trainingIds[8] ?? 9,
                'kategori_id' => $kategoriIds[7] ?? 8,
                'created_by' => $userIds[3] ?? 5,
                'judul' => '🏢 Pelatihan Manajemen Koperasi Modern',
                'deskripsi' => 'Pendaftaran pelatihan manajemen koperasi modern.',
                'konten' => '<h3>Pelatihan Manajemen Koperasi Modern</h3>
                            <p>Dinas Koperindag menyelenggarakan pelatihan <strong>Manajemen Koperasi Modern</strong>.</p>
                            <p><strong>Detail:</strong></p>
                            <ul>
                                <li><strong>Tanggal:</strong> 20-22 Oktober 2026</li>
                                <li><strong>Waktu:</strong> 08:00 - 17:00 WITA</li>
                                <li><strong>Tempat:</strong> Gedung Dinas Koperindag Lantai 3</li>
                                <li><strong>Metode:</strong> Hybrid (Offline & Online)</li>
                            </ul>
                            <p><strong>Materi:</strong></p>
                            <ul>
                                <li>Prinsip dan Tata Kelola Koperasi</li>
                                <li>Manajemen Keuangan Koperasi</li>
                                <li>Pengembangan Anggota</li>
                                <li>Digitalisasi Koperasi</li>
                            </ul>
                            <p>Pendaftaran ditutup pada 15 Oktober 2026.</p>',
                'tanggal' => '2026-10-01',
                'tanggal_selesai' => '2026-10-22',
                'status' => 'draft',
                'is_pinned' => false,
                'target_audience' => 'peserta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 9 - Evaluasi
            [
                'training_id' => null,
                'kategori_id' => null,
                'created_by' => $userIds[0] ?? 2,
                'judul' => '📝 Evaluasi dan Feedback Pelatihan',
                'deskripsi' => 'Mohon mengisi form evaluasi pelatihan yang telah diikuti.',
                'konten' => '<h3>Evaluasi Pelatihan</h3>
                            <p>Kepada seluruh peserta yang telah mengikuti pelatihan di SIPADU, mohon untuk mengisi form evaluasi berikut:</p>
                            <p><strong>Link Form:</strong> <a href="#">sipadu.koperindag.com/evaluasi</a></p>
                            <p>Masukan dan saran Anda sangat berharga bagi kami untuk meningkatkan kualitas pelatihan ke depan.</p>
                            <p>Form evaluasi akan ditutup pada 31 Agustus 2026.</p>
                            <p>Terima kasih atas partisipasi Anda.</p>',
                'tanggal' => '2026-08-20',
                'tanggal_selesai' => '2026-08-31',
                'status' => 'published',
                'is_pinned' => false,
                'target_audience' => 'all',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pengumuman 10 - Info Trainer
            [
                'training_id' => null,
                'kategori_id' => null,
                'created_by' => $userIds[1] ?? 3,
                'judul' => '👨‍🏫 Pengumuman untuk Instruktur',
                'deskripsi' => 'Informasi penting untuk instruktur pelatihan.',
                'konten' => '<h3>Pemberitahuan untuk Instruktur</h3>
                            <p>Kepada seluruh instruktur pelatihan di SIPADU, mohon perhatikan hal berikut:</p>
                            <ol>
                                <li><strong>Modul Pelatihan:</strong> Pastikan modul sudah diupload H-3 sebelum pelaksanaan</li>
                                <li><strong>Absensi:</strong> Gunakan fitur absensi digital di dashboard instruktur</li>
                                <li><strong>Quiz:</strong> Siapkan soal quiz minimal 10 pertanyaan per sesi</li>
                                <li><strong>Evaluasi:</strong> Berikan penilaian objektif kepada peserta</li>
                            </ol>
                            <p>Untuk pertanyaan lebih lanjut, hubungi tim admin.</p>',
                'tanggal' => '2026-07-15',
                'tanggal_selesai' => null,
                'status' => 'published',
                'is_pinned' => false,
                'target_audience' => 'trainer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pengumuman')->insert($pengumuman);

        // ============================================================
        // LOGGING
        // ============================================================
        $this->command->info('✅ Pengumuman seeder berhasil dijalankan!');
        $this->command->info('📢 Total pengumuman: ' . count($pengumuman));
    }
}