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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom foreign key 'bidang_id' yang bisa null
            // dan terhubung ke tabel 'bidangs'. Jika bidang dihapus, kolom ini akan di-set null.
            $table->foreignId('bidang_id')->nullable()->after('id')->constrained('bidangs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus foreign key constraint dan kolom jika migration di-rollback
            $table->dropForeign(['bidang_id']);
            $table->dropColumn('bidang_id');
        });
    }
};