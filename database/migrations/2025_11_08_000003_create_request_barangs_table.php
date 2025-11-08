<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Menggunakan class bernama, bukan 'return new class'
class CreateRequestBarangsTable extends Migration
{
    public function up(): void
    {
        Schema::create('request_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerima_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('barang_donasi_id')->constrained('barang_donasis')->onDelete('cascade');
            $table->text('alasan_permintaan')->nullable();
            $table->string('status')->default('Diajukan');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('request_barangs'); }
};