<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class FetchWilayahSulbar extends Command
{
    protected $signature = 'fetch:wilayah-sulbar';
    protected $description = 'Fetch data wilayah Sulawesi Barat dari API Emsifa dan simpan ke JSON lokal';

    public function handle()
    {
        $this->info('Mulai mengambil data wilayah Sulawesi Barat...');
        $baseUrl = 'https://emsifa.github.io/api-wilayah-indonesia/api';
        $provId = '76';
        
        $regencies = Http::get("$baseUrl/regencies/$provId.json")->json();
        
        if (!$regencies) {
            $this->error('Gagal mengambil data Kabupaten.');
            return;
        }

        $this->info('Berhasil mengambil ' . count($regencies) . ' Kabupaten.');
        
        foreach ($regencies as &$regency) {
            $this->info('Mengambil kecamatan untuk: ' . $regency['name']);
            $districts = Http::get("$baseUrl/districts/{$regency['id']}.json")->json();
            
            foreach ($districts as &$district) {
                $villages = Http::get("$baseUrl/villages/{$district['id']}.json")->json();
                $district['desa'] = array_map(function($v) { return ['id' => $v['id'], 'name' => $v['name']]; }, $villages);
            }
            $regency['kecamatan'] = $districts;
        }

        $path = public_path('data');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        File::put($path . '/wilayah-sulbar.json', json_encode($regencies, JSON_PRETTY_PRINT));
        $this->info('Data wilayah berhasil disimpan di public/data/wilayah-sulbar.json');
    }
}
