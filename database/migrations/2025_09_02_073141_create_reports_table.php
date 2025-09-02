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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('bidang_id')->constrained()->cascadeOnDelete();
            
            // PERUBAHAN 1: Ganti 'pengurus_nama' menjadi 'pengurus_id'
            $table->foreignId('pengurus_id')->constrained()->cascadeOnDelete();
            
            // PERUBAHAN 2: Ganti nama kolom 'bpk_tab' menjadi 'waktu' agar konsisten
            $table->enum('waktu', ['malam', 'subuh'])->nullable();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};