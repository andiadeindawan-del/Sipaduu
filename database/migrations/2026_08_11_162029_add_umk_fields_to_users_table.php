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
            // Data Pribadi
            $table->string('status_pernikahan')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('kode_pos_domisili')->nullable();
            $table->string('disabilitas')->nullable();
            $table->string('ktp_file')->nullable();

            // Data Usaha
            $table->string('jabatan_usaha')->nullable();
            $table->string('merek_produk')->nullable();
            $table->string('kode_pos_usaha')->nullable();
            $table->string('sektor_usaha')->nullable();
            $table->string('no_telepon_usaha')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->date('tanggal_berdiri')->nullable();
            $table->string('npwp_usaha')->nullable();
            $table->string('status_nib')->nullable();
            $table->string('lama_nib')->nullable();
            $table->string('modal_usaha')->nullable();
            $table->decimal('nilai_modal', 15, 2)->nullable();
            $table->string('omzet_usaha')->nullable();
            $table->decimal('nilai_omzet', 15, 2)->nullable();
            $table->integer('jumlah_karyawan')->nullable();
            $table->string('kapasitas_produksi')->nullable();
            $table->string('anggota_koperasi')->nullable();

            // Digitalisasi & Transformasi
            $table->string('email_usaha')->nullable();
            $table->string('website_usaha')->nullable();
            $table->text('medsos_usaha')->nullable();
            $table->text('marketplace')->nullable();
            $table->string('pengadaan_barang')->nullable();
            $table->string('akses_kredit')->nullable();
            $table->string('tabungan')->nullable();
            $table->string('perizinan_usaha')->nullable();
            $table->string('sertifikasi_produk')->nullable();
            $table->string('jangkauan_pemasaran')->nullable();
            $table->string('lokasi_pemasaran')->nullable();
            $table->string('status_ekspor')->nullable();
            $table->string('negara_ekspor')->nullable();
            $table->string('metode_ekspor')->nullable();
            $table->string('volume_ekspor')->nullable();
            $table->decimal('nilai_ekspor', 15, 2)->nullable();
            $table->string('pasok_bahan_baku')->nullable();
            $table->string('kemitraan')->nullable();

            // Informasi Tambahan
            $table->text('permasalahan')->nullable();
            $table->text('kebutuhan_diklat')->nullable();
            $table->string('riwayat_pelatihan')->nullable();
            $table->text('jenis_pelatihan_diikuti')->nullable();
            $table->string('file_produk')->nullable();
            $table->text('masukan_saran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status_pernikahan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama',
                'pendidikan_terakhir', 'kode_pos_domisili', 'disabilitas', 'ktp_file',
                'jabatan_usaha', 'merek_produk', 'kode_pos_usaha', 'sektor_usaha', 'no_telepon_usaha',
                'bidang_usaha', 'tanggal_berdiri', 'npwp_usaha', 'status_nib', 'lama_nib', 'modal_usaha',
                'nilai_modal', 'omzet_usaha', 'nilai_omzet', 'jumlah_karyawan', 'kapasitas_produksi', 'anggota_koperasi',
                'email_usaha', 'website_usaha', 'medsos_usaha', 'marketplace', 'pengadaan_barang', 'akses_kredit',
                'tabungan', 'perizinan_usaha', 'sertifikasi_produk', 'jangkauan_pemasaran', 'lokasi_pemasaran',
                'status_ekspor', 'negara_ekspor', 'metode_ekspor', 'volume_ekspor', 'nilai_ekspor', 'pasok_bahan_baku', 'kemitraan',
                'permasalahan', 'kebutuhan_diklat', 'riwayat_pelatihan', 'jenis_pelatihan_diikuti', 'file_produk', 'masukan_saran'
            ]);
        });
    }
};
