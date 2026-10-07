<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('anggota_koperasi')->nullable();
            $table->text('disabilitas')->nullable();
            $table->integer('jumlah_karyawan')->nullable();
            $table->string('kode_pos_domisili', 10)->nullable();
            $table->string('kode_pos_usaha', 10)->nullable();
            $table->string('lama_nib', 50)->nullable();
            $table->text('marketplace')->nullable();
            $table->text('medsos_usaha')->nullable();
            $table->string('status_nib', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'anggota_koperasi',
                'disabilitas',
                'jumlah_karyawan',
                'kode_pos_domisili',
                'kode_pos_usaha',
                'lama_nib',
                'marketplace',
                'medsos_usaha',
                'status_nib'
            ]);
        });
    }
};
