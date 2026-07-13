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
        // ============================================================
        // ADMIN (1)
        // ============================================================
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nik' => '1234567890123456',
                'nama' => 'Administrator',
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'aktif',
                'no_telepon' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // ============================================================
        // TRAINERS (5)
        // ============================================================
        $trainers = [
            [
                'nik' => '1234567890123457',
                'nama' => 'Dr. Ahmad Fauzi, M.Kom',
                'name' => 'Dr. Ahmad Fauzi, M.Kom',
                'email' => 'ahmad.fauzi@trainer.com',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'status' => 'aktif',
                'no_telepon' => '081234567891',
                'departemen' => 'Teknologi Informasi',
                'jabatan' => 'Instruktur Senior',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123458',
                'nama' => 'Ir. Siti Rahma, M.Si',
                'name' => 'Ir. Siti Rahma, M.Si',
                'email' => 'siti.rahma@trainer.com',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'status' => 'aktif',
                'no_telepon' => '081234567892',
                'departemen' => 'Bisnis Digital',
                'jabatan' => 'Instruktur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123459',
                'nama' => 'M. Iqbal, S.Kom, M.TI',
                'name' => 'M. Iqbal, S.Kom, M.TI',
                'email' => 'iqbal@trainer.com',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'status' => 'aktif',
                'no_telepon' => '081234567893',
                'departemen' => 'Pengembangan SDM',
                'jabatan' => 'Instruktur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123460',
                'nama' => 'Dr. Hj. Nurul Hikmah, S.E.',
                'name' => 'Dr. Hj. Nurul Hikmah, S.E.',
                'email' => 'nurul@trainer.com',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'status' => 'aktif',
                'no_telepon' => '081234567894',
                'departemen' => 'Keuangan & Akuntansi',
                'jabatan' => 'Instruktur Senior',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123461',
                'nama' => 'Rudi Hartono, S.E., M.M.',
                'name' => 'Rudi Hartono, S.E., M.M.',
                'email' => 'rudi@trainer.com',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'status' => 'aktif',
                'no_telepon' => '081234567895',
                'departemen' => 'Kewirausahaan',
                'jabatan' => 'Instruktur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($trainers as $trainer) {
            User::updateOrCreate(
                ['email' => $trainer['email']],
                $trainer
            );
        }

        // ============================================================
        // PARTICIPANTS (10)
        // ============================================================
        $participants = [
            [
                'nik' => '1234567890123462',
                'nama' => 'Budi Santoso',
                'name' => 'Budi Santoso',
                'email' => 'budi@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567896',
                'nama_usaha' => 'Santoso Digital',
                'nib' => '9123456789012',
                'jenis_usaha' => 'formal',
                'alamat_lengkap' => 'Jl. Merdeka No. 45, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123463',
                'nama' => 'Dewi Lestari',
                'name' => 'Dewi Lestari',
                'email' => 'dewi@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567897',
                'nama_usaha' => 'Lestari Craft',
                'nib' => '9123456789013',
                'jenis_usaha' => 'non_formal',
                'alamat_lengkap' => 'Jl. Pahlawan No. 12, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123464',
                'nama' => 'Ahmad Subekti',
                'name' => 'Ahmad Subekti',
                'email' => 'ahmad@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567898',
                'nama_usaha' => 'Subekti Mart',
                'nib' => '9123456789014',
                'jenis_usaha' => 'formal',
                'alamat_lengkap' => 'Jl. Sudirman No. 78, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123465',
                'nama' => 'Rina Marlina',
                'name' => 'Rina Marlina',
                'email' => 'rina@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567899',
                'nama_usaha' => 'Marlina Fashion',
                'nib' => '9123456789015',
                'jenis_usaha' => 'non_formal',
                'alamat_lengkap' => 'Jl. Diponegoro No. 34, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123466',
                'nama' => 'Joko Widodo',
                'name' => 'Joko Widodo',
                'email' => 'joko@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567900',
                'nama_usaha' => 'Widodo Food',
                'nib' => '9123456789016',
                'jenis_usaha' => 'formal',
                'alamat_lengkap' => 'Jl. Gatot Subroto No. 56, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123467',
                'nama' => 'Siti Aminah',
                'name' => 'Siti Aminah',
                'email' => 'siti@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567901',
                'nama_usaha' => 'Aminah Snack',
                'nib' => '9123456789017',
                'jenis_usaha' => 'non_formal',
                'alamat_lengkap' => 'Jl. Hasanuddin No. 23, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123468',
                'nama' => 'Muhammad Rizki',
                'name' => 'Muhammad Rizki',
                'email' => 'rizki@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567902',
                'nama_usaha' => 'Rizki Printing',
                'nib' => '9123456789018',
                'jenis_usaha' => 'formal',
                'alamat_lengkap' => 'Jl. Ahmad Yani No. 67, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123469',
                'nama' => 'Nurhayati',
                'name' => 'Nurhayati',
                'email' => 'nur@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567903',
                'nama_usaha' => 'Nurhayati Kerajinan',
                'nib' => '9123456789019',
                'jenis_usaha' => 'non_formal',
                'alamat_lengkap' => 'Jl. Sisingamangaraja No. 89, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123470',
                'nama' => 'Eko Prasetyo',
                'name' => 'Eko Prasetyo',
                'email' => 'eko@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567904',
                'nama_usaha' => 'Prasetyo Farm',
                'nib' => '9123456789020',
                'jenis_usaha' => 'formal',
                'alamat_lengkap' => 'Jl. Pertanian No. 11, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1234567890123471',
                'nama' => 'Diah Puspita',
                'name' => 'Diah Puspita',
                'email' => 'diah@peserta.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'status' => 'aktif',
                'no_telepon' => '081234567905',
                'nama_usaha' => 'Puspita Beauty',
                'nib' => '9123456789021',
                'jenis_usaha' => 'non_formal',
                'alamat_lengkap' => 'Jl. Kemerdekaan No. 22, Mamuju',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($participants as $participant) {
            User::updateOrCreate(
                ['email' => $participant['email']],
                $participant
            );
        }

        // ============================================================
        // LOGGING
        // ============================================================
        $this->command->info('✅ User seeder berhasil dijalankan!');
        $this->command->info('📊 Total user: ' . User::count());
        $this->command->info('👨‍💼 Admin: 1');
        $this->command->info('👨‍🏫 Trainer: 5');
        $this->command->info('👥 Peserta: 10');
    }
}