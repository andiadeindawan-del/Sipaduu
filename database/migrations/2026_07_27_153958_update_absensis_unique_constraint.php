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
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropUnique('absensis_user_id_tanggal_unique');
            $table->unique(['user_id', 'training_id', 'tanggal'], 'absensis_user_training_tanggal_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropUnique('absensis_user_training_tanggal_unique');
            $table->unique(['user_id', 'tanggal'], 'absensis_user_id_tanggal_unique');
        });
    }
};
