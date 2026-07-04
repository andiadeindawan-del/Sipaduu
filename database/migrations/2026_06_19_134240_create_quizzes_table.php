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
            $table->integer('durasi')->nullable()->comment('Durasi dalam menit');
            $table->decimal('passing_score', 5, 2)->default(70.00)->comment('Nilai minimal lulus');
            $table->integer('max_attempt')->default(1)->comment('Maksimal percobaan');
            $table->boolean('is_random')->default(false)->comment('Acak pertanyaan');
            $table->boolean('show_result')->default(true)->comment('Tampilkan hasil setelah selesai');
            
            // Status & Urutan
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('order')->default(0)->comment('Urutan quiz');
            
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('quizzes');
        Schema::enableForeignKeyConstraints();
    }
};