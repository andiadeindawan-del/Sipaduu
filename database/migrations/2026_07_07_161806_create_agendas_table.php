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
            
            // Foreign Keys
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');
            
            // Informasi Agenda
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            
            // ============================================================
            // JADWAL - Konsisten dengan model
            // ============================================================
            $table->date('tanggal');
            $table->time('waktu_mulai');    // ← Ganti jam_mulai
            $table->time('waktu_selesai');  // ← Ganti jam_selesai
            
            // Lokasi
            $table->string('lokasi', 255)->nullable();
            
            // ============================================================
            // STATUS - TAMBAHKAN INI!
            // ============================================================
            $table->enum('status', [
                'upcoming',      // Akan datang
                'ongoing',       // Sedang berlangsung
                'completed',     // Selesai
                'cancelled',     // Dibatalkan
                'draft',         // Draft
                'published',     // Dipublikasikan
                'selesai'        // Selesai (alternatif)
            ])->default('upcoming');
            
            $table->timestamps();
            
            // ============================================================
            // INDEXES untuk optimasi query
            // ============================================================
            $table->index('status');
            $table->index('tanggal');
            $table->index('training_id');
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