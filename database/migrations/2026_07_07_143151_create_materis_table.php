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
        Schema::disableForeignKeyConstraints();
        
        // ============================================================
        // TABEL MATERIS
        // ============================================================
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('set null');
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('cascade');
            
            // Informasi Materi
            $table->string('judul', 255);
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable();
            
            // ============================================================
            // FILE STORAGE - MULTIPLE FILES WITH JSON
            // ============================================================
            $table->json('file_data')->nullable()->comment('JSON data for multiple files: [{path, url, type, name, size}]');
            
            // Legacy fields (tetap dipertahankan untuk backward compatibility)
            $table->enum('tipe_file', ['pdf', 'video', 'ppt', 'link', 'image', 'other'])->nullable();
            $table->string('file_url')->nullable();
            $table->string('file_path')->nullable();
            
            // Metadata
            $table->integer('durasi')->nullable()->comment('Durasi dalam menit');
            $table->integer('order')->default(0)->comment('Urutan materi');
            $table->boolean('is_free')->default(false)->comment('Materi gratis atau berbayar');
            $table->integer('total_files')->default(0)->comment('Total jumlah file');
            
            // Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index('status');
            $table->index('kategori_id');
            $table->index('training_id');
            $table->index('order');
            $table->index('slug');
        });

        // ============================================================
        // TABEL MATERI PROGRESS
        // ============================================================
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materis')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->integer('progress')->default(0)->comment('Progress dalam persentase (0-100)');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['materi_id', 'user_id']);
            
            // Index untuk optimasi query
            $table->index('status');
            $table->index('progress');
            $table->index(['materi_id', 'user_id']);
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('materi_progress');
        Schema::dropIfExists('materis');
        Schema::enableForeignKeyConstraints();
    }
};