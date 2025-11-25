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
    Schema::table('barang_donasis', function (Blueprint $table) {
        // Menambahkan kolom kabupaten jika belum ada
        if (!Schema::hasColumn('barang_donasis', 'kabupaten')) {
            $table->string('kabupaten')->nullable(); // Kolom kabupaten
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom kabupaten dari tabel barang_donasis
        Schema::table('barang_donasis', function (Blueprint $table) {
            $table->dropColumn('kabupaten');
        });
    }
};
