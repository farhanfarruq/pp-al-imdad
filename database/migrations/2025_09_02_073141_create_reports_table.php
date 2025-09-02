<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('reports', function (Blueprint $table) {
$table->id();
$table->date('tanggal');
$table->foreignId('bidang_id')->constrained()->cascadeOnDelete();
$table->string('pengurus_nama'); // simple, atau relasi ke tabel penguruses
$table->enum('bpk_tab', ['malam','subuh'])->nullable();
$table->foreignId('user_id')->constrained()->cascadeOnDelete(); // yg submit
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('reports'); }
};