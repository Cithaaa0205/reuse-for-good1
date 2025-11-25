<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangDonasisTable extends Migration
{
    public function up(): void
    {
        Schema::create('barang_donasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donatur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategoris');

            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->string('kondisi');

            // =================== Lokasi ===================
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            // ==============================================

            $table->string('alamat_lengkap')->nullable();

            // =================== Foto =====================
            $table->string('foto_barang_utama')->nullable();
            $table->json('foto_barang_lainnya')->nullable();
            // ==============================================

            $table->text('catatan_pengambilan')->nullable();
            $table->string('status')->default('Tersedia');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_donasis');
    }
}
