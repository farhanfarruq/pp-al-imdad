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
        Schema::create('jobdesks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->text('deskripsi');
            $table->enum('waktu', ['malam', 'subuh'])->nullable(); // Kolom 'waktu' ditambahkan di sini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobdesks');
    }
};