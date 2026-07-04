<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            
            // ============================================================
            // INFORMASI PERTANYAAN (Dual Field untuk kompatibilitas)
            // ============================================================
            $table->text('question')->nullable()->comment('Pertanyaan (format baru)');
            $table->text('pertanyaan')->nullable()->comment('Pertanyaan (format lama)');
            
            // ============================================================
            // TIPE SOAL (Dual Field untuk kompatibilitas)
            // ============================================================
            $table->enum('type', ['multiple_choice', 'true_false', 'essay'])->default('multiple_choice')->comment('Tipe soal (format baru)');
            $table->enum('tipe_soal', ['pilihan', 'essay', 'true_false'])->nullable()->comment('Tipe soal (format lama)');
            
            // ============================================================
            // NILAI (Dual Field untuk kompatibilitas)
            // ============================================================
            $table->integer('score')->default(1)->comment('Nilai per soal (format baru)');
            $table->integer('nilai')->default(1)->comment('Nilai per soal (format lama)');
            
            // ============================================================
            // PILIHAN JAWABAN (Dual Field untuk kompatibilitas)
            // ============================================================
            $table->json('options')->nullable()->comment('Pilihan jawaban JSON (format baru)');
            
            // Legacy fields untuk pilihan jawaban
            $table->string('opsi_a')->nullable()->comment('Pilihan A (format lama)');
            $table->string('opsi_b')->nullable()->comment('Pilihan B (format lama)');
            $table->string('opsi_c')->nullable()->comment('Pilihan C (format lama)');
            $table->string('opsi_d')->nullable()->comment('Pilihan D (format lama)');
            $table->string('opsi_e')->nullable()->comment('Pilihan E (format lama)');
            
            // ============================================================
            // JAWABAN BENAR (Dual Field untuk kompatibilitas)
            // ============================================================
            $table->string('correct_answer')->nullable()->comment('Jawaban benar (format baru)');
            $table->string('jawaban_benar')->nullable()->comment('Jawaban benar (format lama)');
            
            // ============================================================
            // PENGATURAN
            // ============================================================
            $table->integer('order')->default(0)->comment('Urutan soal');
            
            $table->timestamps();
            
            // ============================================================
            // INDEX
            // ============================================================
            $table->index('quiz_id');
            $table->index('type');
            $table->index('tipe_soal');
            $table->index('order');
            $table->index('score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};