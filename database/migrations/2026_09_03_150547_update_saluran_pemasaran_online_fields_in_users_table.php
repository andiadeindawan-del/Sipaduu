<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('judul_usaha_online')->nullable()->after('website_usaha');
            $table->string('shopee')->nullable()->after('tiktok_usaha');
            $table->string('tokopedia')->nullable()->after('shopee');
            $table->string('lazada')->nullable()->after('tokopedia');
            $table->string('blibli')->nullable()->after('lazada');
            $table->renameColumn('marketplace', 'marketplace_lainnya');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'judul_usaha_online',
                'shopee',
                'tokopedia',
                'lazada',
                'blibli'
            ]);
            $table->renameColumn('marketplace_lainnya', 'marketplace');
        });
    }
};

