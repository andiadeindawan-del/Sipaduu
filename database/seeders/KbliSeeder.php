<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kbli;
use Illuminate\Support\Facades\File;

class KbliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFile = database_path('data/kbli_2025_koperindag.json');
        
        if (File::exists($jsonFile)) {
            $jsonData = File::get($jsonFile);
            $kblis = json_decode($jsonData, true);

            foreach ($kblis as $kbli) {
                Kbli::updateOrCreate(
                    ['kode' => $kbli['kode']],
                    [
                        'judul' => $kbli['judul'] ?? $kbli['kelompok_nama'] ?? null,
                        'uraian' => $kbli['uraian'] ?? null,
                        'versi' => $kbli['versi'] ?? 'KBLI 2025',
                        'kategori' => $kbli['kategori'] ?? ($kbli['kategori_kode'] ? $kbli['kategori_kode'] . ' - ' . $kbli['kategori_nama'] : null),
                        'kategori_kode' => $kbli['kategori_kode'] ?? null,
                        'kategori_nama' => $kbli['kategori_nama'] ?? null,
                        'golongan_pokok_kode' => $kbli['golongan_pokok_kode'] ?? null,
                        'golongan_pokok_nama' => $kbli['golongan_pokok_nama'] ?? null,
                        'golongan_kode' => $kbli['golongan_kode'] ?? null,
                        'golongan_nama' => $kbli['golongan_nama'] ?? null,
                        'subgolongan_kode' => $kbli['subgolongan_kode'] ?? null,
                        'subgolongan_nama' => $kbli['subgolongan_nama'] ?? null,
                        'kelompok_kode' => $kbli['kelompok_kode'] ?? null,
                        'kelompok_nama' => $kbli['kelompok_nama'] ?? null,
                        'aktif' => $kbli['aktif'] ?? true,
                    ]
                );
            }
            
            $this->command->info('KBLI data seeded successfully from local JSON.');
        } else {
            $this->command->error('KBLI JSON dataset not found at: ' . $jsonFile);
        }
    }
}
