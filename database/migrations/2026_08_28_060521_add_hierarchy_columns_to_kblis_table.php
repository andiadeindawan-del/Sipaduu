<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kblis', function (Blueprint $table) {
            // Check if column doesn't exist before adding to ensure safety
            if (!Schema::hasColumn('kblis', 'kategori_kode')) {
                $table->string('kategori_kode', 5)->nullable()->after('versi');
                $table->string('kategori_nama')->nullable()->after('kategori_kode');
                $table->string('golongan_pokok_kode', 5)->nullable()->after('kategori_nama');
                $table->string('golongan_pokok_nama')->nullable()->after('golongan_pokok_kode');
                $table->string('golongan_kode', 5)->nullable()->after('golongan_pokok_nama');
                $table->string('golongan_nama')->nullable()->after('golongan_kode');
                $table->string('subgolongan_kode', 5)->nullable()->after('golongan_nama');
                $table->string('subgolongan_nama')->nullable()->after('subgolongan_kode');
                $table->string('kelompok_kode', 10)->nullable()->after('subgolongan_nama');
                $table->string('kelompok_nama')->nullable()->after('kelompok_kode');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kblis', function (Blueprint $table) {
            $columns = [
                'kategori_kode', 'kategori_nama', 
                'golongan_pokok_kode', 'golongan_pokok_nama',
                'golongan_kode', 'golongan_nama',
                'subgolongan_kode', 'subgolongan_nama',
                'kelompok_kode', 'kelompok_nama'
            ];
            
            foreach($columns as $col) {
                if (Schema::hasColumn('kblis', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
