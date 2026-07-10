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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            
            // ============================================================
            // FOREIGN KEYS
            // ============================================================
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            // ============================================================
            // INFORMASI AGENDA
            // ============================================================
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            
            // ============================================================
            // JADWAL - PERBAIKAN: gunakan jam_mulai dan jam_selesai
            // ============================================================
            $table->date('tanggal');
            $table->time('jam_mulai');                    // ← PERBAIKAN: jam_mulai
            $table->time('jam_selesai')->nullable();      // ← PERBAIKAN: jam_selesai (nullable)
            
            // ============================================================
            // LOKASI & TIPE - TAMBAHKAN KOLOM YANG KURANG
            // ============================================================
            $table->string('lokasi', 255)->nullable();
            $table->string('link_meeting', 255)->nullable();  // ← TAMBAHKAN
            
            // ============================================================
            // TIPE AGENDA - TAMBAHKAN INI!
            // ============================================================
            $table->enum('tipe', ['online', 'offline', 'hybrid'])->default('online');
            
            // ============================================================
            // STATUS - PERBAIKI
            // ============================================================
            $table->enum('status', [
                'draft',         // Draft
                'published',     // Dipublikasikan
                'selesai',       // Selesai
                'dibatalkan'     // Dibatalkan
            ])->default('draft');
            
            $table->timestamps();
            
            // ============================================================
            // INDEXES untuk optimasi query
            // ============================================================
            $table->index('status');
            $table->index('tanggal');
            $table->index('training_id');
            $table->index('tipe');                    // ← TAMBAHKAN
            $table->index('created_by');              // ← TAMBAHKAN
            $table->index(['tanggal', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};