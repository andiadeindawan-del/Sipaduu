<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Informasi Pengumuman
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable();
            
            // Jadwal
            $table->date('tanggal');
            $table->date('tanggal_selesai')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            
            // Pinned
            $table->boolean('is_pinned')->default(false);
            
            // Target Audience
            $table->enum('target_audience', ['all', 'peserta', 'trainer', 'admin'])->default('all');
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('tanggal');
            $table->index('tanggal_selesai');
            $table->index('is_pinned');
            $table->index('training_id');
            $table->index('kategori_id');
            $table->index('created_by');
            $table->index('target_audience');
            $table->index(['status', 'tanggal']);
            $table->index(['is_pinned', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};