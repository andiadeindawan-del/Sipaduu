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
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_usaha', 255)->nullable()->after('tiktok_usaha');
            $table->string('youtube_usaha', 255)->nullable()->after('whatsapp_usaha');
            $table->string('bukalapak', 255)->nullable()->after('blibli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_usaha', 'youtube_usaha', 'bukalapak']);
        });
    }
};
