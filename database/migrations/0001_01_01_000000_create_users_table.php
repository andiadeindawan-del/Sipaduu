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
        Schema::create('users', function (Blueprint $table) {
            // ============================================================
            // PRIMARY KEY & BASIC AUTH
            // ============================================================
            $table->id();
            $table->string('name')->nullable();  // ← Tambahkan untuk kompatibilitas Laravel
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // ============================================================
            // IDENTITY & ROLE
            // ============================================================
            $table->string('nik', 30)->unique()->nullable();
            $table->string('nama', 100)->nullable();
            $table->enum('role', ['admin', 'trainer', 'peserta'])->default('peserta');

            // ============================================================
            // CONTACT
            // ============================================================
            $table->string('no_telepon', 20)->nullable();

            // ============================================================
            // BUSINESS DATA (untuk peserta)
            // ============================================================
            $table->string('nama_usaha', 150)->nullable();
            $table->string('nib', 50)->nullable();
            $table->enum('jenis_usaha', ['formal', 'non_formal'])->nullable();
            $table->text('alamat_lengkap')->nullable();

            // ============================================================
            // EMPLOYEE DATA (untuk admin/trainer)
            // ============================================================
            $table->string('departemen', 100)->nullable();
            $table->string('jabatan', 100)->nullable();

            // ============================================================
            // PROFILE & STATUS
            // ============================================================
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            // ============================================================
            // TIMESTAMPS
            // ============================================================
            $table->timestamps();

            // ============================================================
            // INDEXES
            // ============================================================
            $table->index('role');
            $table->index('status');
            $table->index('email');
        });

        // ============================================================
        // PASSWORD RESET TOKENS
        // ============================================================
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ============================================================
        // SESSIONS
        // ============================================================
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};