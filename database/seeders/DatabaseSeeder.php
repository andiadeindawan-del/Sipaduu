<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================================
        // HAPUS ATAU KOMENTARI SEEDER DEFAULT LARAVEL
        // ============================================================
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // ============================================================
        // PAKAI SEEDER ANDA SENDIRI
        // ============================================================
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            TrainingSeeder::class,
            AgendaSeeder::class,
            PengumumanSeeder::class,
        ]);
    }
}