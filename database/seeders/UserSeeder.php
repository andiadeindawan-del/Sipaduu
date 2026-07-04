<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nik' => '1234567890123456',
            'nama' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
            'no_telepon' => '081234567890',
        ]);

        User::create([
            'nik' => '1234567890123457',
            'nama' => 'Trainer',
            'email' => 'trainer@example.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'status' => 'aktif',
            'no_telepon' => '081234567891',
            'departemen' => 'Pelatihan',
            'jabatan' => 'Instruktur',
        ]);

        User::create([
            'nik' => '1234567890123458',
            'nama' => 'Peserta',
            'email' => 'peserta@example.com',
            'password' => Hash::make('password'),
            'role' => 'peserta',
            'status' => 'aktif',
            'no_telepon' => '081234567892',
            'nama_usaha' => 'UMKM Maju Jaya',
            'nib' => '9123456789012',
            'jenis_usaha' => 'formal',
            'alamat_lengkap' => 'Jl. Contoh No. 123',
        ]);
    }
}