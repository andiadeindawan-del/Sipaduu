<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Teknologi Informasi',
                'slug' => Str::slug('Teknologi Informasi'),
                'deskripsi' => 'Pelatihan di bidang teknologi informasi, pemrograman, dan pengembangan sistem digital.',
                'icon' => 'bi-laptop',
                'warna' => '#4e9af1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bisnis Digital',
                'slug' => Str::slug('Bisnis Digital'),
                'deskripsi' => 'Pelatihan tentang strategi bisnis digital, pemasaran online, dan e-commerce.',
                'icon' => 'bi-graph-up-arrow',
                'warna' => '#28c76f',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Manajemen SDM',
                'slug' => Str::slug('Manajemen SDM'),
                'deskripsi' => 'Pelatihan tentang pengelolaan sumber daya manusia, rekrutmen, dan pengembangan karyawan.',
                'icon' => 'bi-people',
                'warna' => '#ff9f43',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Keuangan & Akuntansi',
                'slug' => Str::slug('Keuangan Akuntansi'),
                'deskripsi' => 'Pelatihan tentang manajemen keuangan, akuntansi, dan analisis laporan keuangan.',
                'icon' => 'bi-coin',
                'warna' => '#17a2b8',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pemasaran & Branding',
                'slug' => Str::slug('Pemasaran Branding'),
                'deskripsi' => 'Pelatihan tentang strategi pemasaran, branding, dan public relations.',
                'icon' => 'bi-megaphone',
                'warna' => '#ea5455',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kewirausahaan',
                'slug' => Str::slug('Kewirausahaan'),
                'deskripsi' => 'Pelatihan tentang kewirausahaan, inovasi bisnis, dan pengembangan usaha.',
                'icon' => 'bi-rocket',
                'warna' => '#6c5ce7',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Komunikasi',
                'slug' => Str::slug('Komunikasi'),
                'deskripsi' => 'Pelatihan tentang komunikasi efektif, public speaking, dan negosiasi.',
                'icon' => 'bi-chat-dots',
                'warna' => '#fd79a8',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Koperasi & UMKM',
                'slug' => Str::slug('Koperasi UMKM'),
                'deskripsi' => 'Pelatihan tentang pengelolaan koperasi dan pengembangan usaha mikro kecil menengah.',
                'icon' => 'bi-shop',
                'warna' => '#00b894',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Legal & Hukum',
                'slug' => Str::slug('Legal Hukum'),
                'deskripsi' => 'Pelatihan tentang aspek hukum bisnis, perizinan, dan regulasi usaha.',
                'icon' => 'bi-balance',
                'warna' => '#6c757d',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kepemimpinan',
                'slug' => Str::slug('Kepemimpinan'),
                'deskripsi' => 'Pelatihan tentang kepemimpinan, manajemen tim, dan pengembangan diri.',
                'icon' => 'bi-trophy',
                'warna' => '#fdcb6e',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('kategoris')->insert($kategoris);
    }
}