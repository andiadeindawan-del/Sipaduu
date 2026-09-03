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
            // Remove unneeded columns
            $table->dropColumn([
                'disabilitas',
                'kode_pos_domisili',
                'kode_pos_usaha',
                'anggota_koperasi',
                'status_nib',
                'lama_nib',
                'jumlah_karyawan',
                'medsos_usaha'
            ]);

            // Add new required columns
            $table->string('status_usaha')->nullable()->after('nama_usaha');
            $table->string('bentuk_usaha')->nullable()->after('status_usaha');
            $table->integer('jumlah_karyawan_laki_laki')->nullable()->after('nilai_omzet');
            $table->integer('jumlah_karyawan_perempuan')->nullable()->after('jumlah_karyawan_laki_laki');
            $table->integer('total_karyawan')->nullable()->after('jumlah_karyawan_perempuan');
            
            // New Address for Usaha
            $table->string('provinsi_usaha')->nullable()->after('kapasitas_produksi');
            $table->string('kabupaten_usaha')->nullable()->after('provinsi_usaha');
            $table->string('kecamatan_usaha')->nullable()->after('kabupaten_usaha');
            $table->string('desa_usaha')->nullable()->after('kecamatan_usaha');
            $table->text('alamat_usaha')->nullable()->after('desa_usaha');

            // Social Media
            $table->string('facebook_usaha')->nullable()->after('website_usaha');
            $table->string('instagram_usaha')->nullable()->after('facebook_usaha');
            $table->string('tiktok_usaha')->nullable()->after('instagram_usaha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('disabilitas')->nullable();
            $table->string('kode_pos_domisili')->nullable();
            $table->string('kode_pos_usaha')->nullable();
            $table->string('anggota_koperasi')->nullable();
            $table->string('status_nib')->nullable();
            $table->string('lama_nib')->nullable();
            $table->integer('jumlah_karyawan')->nullable();
            $table->text('medsos_usaha')->nullable();

            $table->dropColumn([
                'status_usaha',
                'bentuk_usaha',
                'jumlah_karyawan_laki_laki',
                'jumlah_karyawan_perempuan',
                'total_karyawan',
                'provinsi_usaha',
                'kabupaten_usaha',
                'kecamatan_usaha',
                'desa_usaha',
                'alamat_usaha',
                'facebook_usaha',
                'instagram_usaha',
                'tiktok_usaha'
            ]);
        });
    }
};
