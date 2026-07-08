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
        Schema::disableForeignKeyConstraints();  // ← Tambahkan ini di awal
        
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('agenda_id')->nullable()->constrained('agendas')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');
            
            // Tanggal dan Waktu
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->timestamp('waktu_checkin')->nullable();
            $table->timestamp('waktu_checkout')->nullable();
            
            // ============================================================
            // STATUS - SESUAIKAN DENGAN MODEL
            // ============================================================
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');
            $table->enum('status_hadir', ['hadir', 'izin', 'alpha'])->nullable();
            
            // Keterangan
            $table->text('keterangan')->nullable();
            
            // Lokasi (opsional)
            $table->string('lokasi')->nullable();
            $table->string('ip_address', 45)->nullable();
            
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index('tanggal');
            $table->index('status');
            $table->index('agenda_id');
            $table->index('training_id');
            $table->index('user_id');
            
            // Unique constraint
            $table->unique(['user_id', 'tanggal']);
            $table->unique(['agenda_id', 'user_id']);
        });

        Schema::enableForeignKeyConstraints();  // ← Tambahkan ini di akhir
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('absensis');
        Schema::enableForeignKeyConstraints();
    }
};