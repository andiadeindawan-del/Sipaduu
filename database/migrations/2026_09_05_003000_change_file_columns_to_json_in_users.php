<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First convert the columns to TEXT
        DB::statement("ALTER TABLE users MODIFY npwp_file TEXT NULL");
        DB::statement("ALTER TABLE users MODIFY file_produk TEXT NULL");

        // Convert existing string paths to JSON arrays
        $users = DB::table("users")->whereNotNull("npwp_file")->orWhereNotNull("file_produk")->get();
        foreach($users as $u) {
            $update = [];
            
            if ($u->npwp_file && !is_array(json_decode($u->npwp_file, true))) {
                $update["npwp_file"] = json_encode([$u->npwp_file]);
            }
            
            if ($u->file_produk && !is_array(json_decode($u->file_produk, true))) {
                $update["file_produk"] = json_encode([$u->file_produk]);
            }
            
            if (!empty($update)) {
                DB::table("users")->where("id", $u->id)->update($update);
            }
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY npwp_file VARCHAR(255) NULL");
        DB::statement("ALTER TABLE users MODIFY file_produk VARCHAR(255) NULL");
    }
};
