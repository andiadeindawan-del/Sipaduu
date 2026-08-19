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
            $table->string('jenis_usaha')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Note: Reverting back to ENUM might fail if the data contains values other than formal/non_formal
            // So we just keep it as string or change it back if needed.
            // $table->enum('jenis_usaha', ['formal', 'non_formal'])->nullable()->change();
        });
    }
};
