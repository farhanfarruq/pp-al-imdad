<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('penguruses', function (Blueprint $table) {
$table->id();
$table->string('nama');
$table->foreignId('bidang_id')->constrained()->cascadeOnDelete();
$table->string('kelas')->nullable(); // khusus bapakamar: 7..12/tahfidz
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('penguruses'); }
};