<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable();
            $table->string('warna', 20)->nullable();
            $table->timestamps();  
            
            // Index untuk optimasi
            $table->index('nama');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};