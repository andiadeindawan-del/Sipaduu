<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First delete duplicates to allow the unique index to be created
        DB::statement('
            DELETE t1 FROM user_kblis t1
            INNER JOIN user_kblis t2 
            WHERE 
                t1.id < t2.id AND 
                t1.user_id = t2.user_id AND 
                t1.kbli_id = t2.kbli_id
        ');

        Schema::table('user_kblis', function (Blueprint $table) {
            $indexes = Schema::getIndexes('user_kblis');
            $hasIndex = false;
            foreach ($indexes as $index) {
                if ($index['name'] === 'user_kblis_user_id_kbli_id_unique') {
                    $hasIndex = true;
                    break;
                }
            }
            if (!$hasIndex) {
                $table->unique(['user_id', 'kbli_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_kblis', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'kbli_id']);
        });
    }
};
