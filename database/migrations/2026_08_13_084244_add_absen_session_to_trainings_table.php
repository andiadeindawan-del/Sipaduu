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
        Schema::table('trainings', function (Blueprint $table) {
            $table->boolean('is_absen_open')->default(false)->after('status');
            $table->string('absen_token')->nullable()->after('is_absen_open');
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->string('metode')->nullable()->after('status_hadir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn(['is_absen_open', 'absen_token']);
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('metode');
        });
    }
};
