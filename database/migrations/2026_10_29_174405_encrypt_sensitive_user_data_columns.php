<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop existing unique index on nik
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_nik_unique');
        });

        // 2. Change column types to TEXT to support AES-256 ciphertext strings (~200+ chars)
        // and add nik_hash column for blind indexing (unique checking & exact lookup)
        Schema::table('users', function (Blueprint $table) {
            $table->text('nik')->nullable()->change();
            $table->text('npwp_usaha')->nullable()->change();
            $table->string('nik_hash', 64)->nullable()->unique()->after('nik');
        });

        // 3. Encrypt existing plain data in database
        $key = config('app.key');
        $users = DB::table('users')->select('id', 'nik', 'npwp_usaha')->get();

        foreach ($users as $user) {
            $updates = [];

            if (!empty($user->nik)) {
                $isEncrypted = false;
                try {
                    Crypt::decryptString($user->nik);
                    $isEncrypted = true;
                } catch (\Throwable $e) {
                    $isEncrypted = false;
                }

                if (!$isEncrypted) {
                    $updates['nik'] = Crypt::encryptString((string) $user->nik);
                    $updates['nik_hash'] = hash_hmac('sha256', (string) $user->nik, (string) $key);
                } else {
                    $plainNik = Crypt::decryptString($user->nik);
                    $updates['nik_hash'] = hash_hmac('sha256', (string) $plainNik, (string) $key);
                }
            }

            if (!empty($user->npwp_usaha)) {
                $isEncrypted = false;
                try {
                    Crypt::decryptString($user->npwp_usaha);
                    $isEncrypted = true;
                } catch (\Throwable $e) {
                    $isEncrypted = false;
                }

                if (!$isEncrypted) {
                    $updates['npwp_usaha'] = Crypt::encryptString((string) $user->npwp_usaha);
                }
            }

            if (!empty($updates)) {
                DB::table('users')->where('id', $user->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Decrypt data back to plain text before reducing column sizes
        $users = DB::table('users')->select('id', 'nik', 'npwp_usaha')->get();

        foreach ($users as $user) {
            $updates = [];

            if (!empty($user->nik)) {
                try {
                    $updates['nik'] = substr(Crypt::decryptString($user->nik), 0, 30);
                } catch (\Throwable $e) {}
            }

            if (!empty($user->npwp_usaha)) {
                try {
                    $updates['npwp_usaha'] = substr(Crypt::decryptString($user->npwp_usaha), 0, 255);
                } catch (\Throwable $e) {}
            }

            if (!empty($updates)) {
                DB::table('users')->where('id', $user->id)->update($updates);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nik_hash']);
            $table->dropColumn('nik_hash');
            $table->string('nik', 30)->nullable()->change();
            $table->unique('nik', 'users_nik_unique');
            $table->string('npwp_usaha', 255)->nullable()->change();
        });
    }
};
