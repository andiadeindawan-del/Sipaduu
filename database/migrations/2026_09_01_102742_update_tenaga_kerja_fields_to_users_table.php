<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('jumlah_karyawan_laki_laki', 'karyawan_tetap_laki_laki');
            $table->renameColumn('jumlah_karyawan_perempuan', 'karyawan_tetap_perempuan');
            $table->renameColumn('total_karyawan', 'total_tenaga_kerja');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_karyawan_tetap')->default(0)->after('karyawan_tetap_perempuan');
            $table->integer('karyawan_tidak_tetap_laki_laki')->default(0)->after('total_karyawan_tetap');
            $table->integer('karyawan_tidak_tetap_perempuan')->default(0)->after('karyawan_tidak_tetap_laki_laki');
            $table->integer('total_karyawan_tidak_tetap')->default(0)->after('karyawan_tidak_tetap_perempuan');
        });
        
        DB::statement("UPDATE users SET total_karyawan_tetap = total_tenaga_kerja");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'total_karyawan_tetap',
                'karyawan_tidak_tetap_laki_laki',
                'karyawan_tidak_tetap_perempuan',
                'total_karyawan_tidak_tetap',
            ]);
            
            $table->renameColumn('karyawan_tetap_laki_laki', 'jumlah_karyawan_laki_laki');
            $table->renameColumn('karyawan_tetap_perempuan', 'jumlah_karyawan_perempuan');
            $table->renameColumn('total_tenaga_kerja', 'total_karyawan');
        });
    }
};

