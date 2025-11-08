<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('role'); // Untuk menyimpan nama file foto
            $table->text('deskripsi')->nullable()->after('foto_profil'); // Untuk bio/deskripsi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
            $table->dropColumn('deskripsi');
        });
    }
};