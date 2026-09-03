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
        Schema::create('kblis', function (Blueprint $table) {
            
            $table->id();
            $table->string('kode')->unique();
            $table->string('judul');
            $table->text('uraian')->nullable();
            $table->string('versi')->default('KBLI 2025');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kblis');
    }
};
