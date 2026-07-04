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
        // ============================================================
        // TABEL SERTIFIKATS
        // ============================================================
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');
            
            // Informasi Sertifikat
            $table->string('nomor_sertifikat', 50)->unique();
            $table->string('nama_sertifikat', 150);
            $table->text('deskripsi')->nullable();
            
            // Tanggal
            $table->date('tanggal_terbit');
            $table->date('tanggal_berlaku_sampai')->nullable();
            
            // Penerbit & File
            $table->string('penerbit', 100);
            $table->string('file_path')->nullable();
            $table->string('tanda_tangan_digital')->nullable();
            
            // Catatan
            $table->text('catatan')->nullable();
            
            // Status
            $table->enum('status', ['aktif', 'revoked', 'expired'])->default('aktif');
            
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index('user_id');
            $table->index('training_id');
            $table->index('status');
            $table->index('nomor_sertifikat');
            $table->index('tanggal_terbit');
            
            // Composite index
            $table->index(['user_id', 'training_id']);
            $table->index(['status', 'tanggal_terbit']);
        });

        // ============================================================
        // TAMBAHKAN CERTIFICATE_ID KE TRAINING_PARTICIPANTS
        // ============================================================
        Schema::table('training_participants', function (Blueprint $table) {
            // Cek apakah kolom certificate_id sudah ada
            if (!Schema::hasColumn('training_participants', 'certificate_id')) {
                $table->foreignId('certificate_id')->nullable()->after('completed_at')->constrained('sertifikats')->onDelete('set null');
                $table->index('certificate_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus certificate_id dari training_participants
        Schema::table('training_participants', function (Blueprint $table) {
            if (Schema::hasColumn('training_participants', 'certificate_id')) {
                $table->dropForeign(['certificate_id']);
                $table->dropColumn('certificate_id');
            }
        });

        // Hapus tabel sertifikats
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('sertifikats');
        Schema::enableForeignKeyConstraints();
    }
};