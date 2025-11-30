<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // User pelapor
            $table->foreignId('reporter_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Target laporan
            // reported_type: user / barang / pesan
            $table->string('reported_type');
            $table->unsignedBigInteger('reported_id');

            // Isi laporan singkat
            $table->string('reason');

            // Status laporan: baru / diproses / selesai
            $table->string('status')->default('baru');

            // Admin yang menangani
            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('handled_at')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            $table->index(['reported_type', 'reported_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
