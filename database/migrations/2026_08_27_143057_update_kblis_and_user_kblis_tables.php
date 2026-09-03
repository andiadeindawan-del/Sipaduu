<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update kblis table
        Schema::table('kblis', function (Blueprint $table) {
            if (!Schema::hasColumn('kblis', 'kategori')) {
                $table->string('kategori')->nullable()->after('versi');
            }
            if (!Schema::hasColumn('kblis', 'aktif')) {
                $table->boolean('aktif')->default(true)->after('kategori');
            }
        });

        // 2. Clear old data from user_kblis to prevent foreign key errors if table exists
        DB::table('user_kblis')->truncate();

        // 3. Update user_kblis table
        Schema::table('user_kblis', function (Blueprint $table) {
            if (Schema::hasColumn('user_kblis', 'kode_kbli')) {
                $table->dropColumn('kode_kbli');
            }
            if (Schema::hasColumn('user_kblis', 'nama_kbli')) {
                $table->dropColumn('nama_kbli');
            }
            if (!Schema::hasColumn('user_kblis', 'kbli_id')) {
                // Add kbli_id as foreign key
                $table->foreignId('kbli_id')->after('user_id')->constrained('kblis')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_kblis', function (Blueprint $table) {
            $table->dropForeign(['kbli_id']);
            $table->dropColumn('kbli_id');
            $table->string('kode_kbli')->nullable();
            $table->string('nama_kbli')->nullable();
        });

        Schema::table('kblis', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'aktif']);
        });
    }
};
