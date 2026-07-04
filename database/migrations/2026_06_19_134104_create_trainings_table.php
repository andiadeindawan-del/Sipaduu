<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // TABEL TRAININGS
        // ============================================================
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            
            // FOREIGN KEYS
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('set null');
            $table->foreignId('trainer_id')->nullable()->constrained('users')->onDelete('set null');
            
            // INFORMASI TRAINING
            $table->string('judul', 150);
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['online', 'offline', 'hybrid'])->default('offline');
            
            // LOKASI & LINK
            $table->string('lokasi', 150)->nullable();
            $table->string('link_meeting')->nullable();
            
            // TANGGAL
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // KAPASITAS
            $table->integer('kapasitas')->nullable();
            
            // STATUS
            $table->enum('status', ['draft', 'published', 'berjalan', 'selesai', 'dibatalkan'])->default('draft');
            
            // GAMBAR
            $table->string('gambar')->nullable();
            
            // TIMESTAMPS
            $table->timestamps();
            
            // INDEXES
            $table->index('status');
            $table->index('tanggal_mulai');
            $table->index('tanggal_selesai');
            $table->index('kategori_id');
            $table->index('trainer_id');
        });

        // ============================================================
        // TABEL TRAINING PARTICIPANTS (PIVOT)
        // ============================================================
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['registered', 'attended', 'completed', 'cancelled'])->default('registered');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            // HAPUS certificate_id dari sini (akan ditambahkan di migration sertifikats)
            $table->timestamps();

            // Unique constraint
            $table->unique(['training_id', 'user_id']);
            
            // Index
            $table->index('status');
            $table->index('registered_at');
            $table->index('completed_at');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('training_participants');
        Schema::dropIfExists('trainings');
        Schema::enableForeignKeyConstraints();
    }
};