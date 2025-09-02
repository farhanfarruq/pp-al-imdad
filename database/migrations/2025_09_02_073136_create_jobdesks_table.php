<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('jobdesks', function (Blueprint $table) {
$table->id();
$table->foreignId('bidang_id')->constrained()->cascadeOnDelete();
$table->string('label');
$table->enum('bpk_tab', ['malam','subuh'])->nullable(); // hanya untuk bidang bapakamar
$table->boolean('is_active')->default(true);
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('jobdesks'); }
};