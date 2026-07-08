<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();  // ← Tambahkan ini
        
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');
            $table->foreignId('materi_id')->nullable()->constrained('materis')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Informasi Quiz
            $table->string('judul', 150);
            $table->text('deskripsi')->nullable();
            
            // Pengaturan
            $table->integer('durasi')->nullable();
            $table->decimal('passing_score', 5, 2)->default(70.00);
            $table->integer('max_attempt')->default(1);
            $table->boolean('is_random')->default(false);
            $table->boolean('show_result')->default(true);
            
            // Status & Urutan
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('order')->default(0);
            
            // Jadwal
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('status');
            $table->index('training_id');
            $table->index('materi_id');
            $table->index('order');
            $table->index('created_by');
            $table->index(['status', 'start_date', 'end_date']);
        });
        
        Schema::enableForeignKeyConstraints();  // ← Tambahkan ini
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('quizzes');
        Schema::enableForeignKeyConstraints();
    }
};