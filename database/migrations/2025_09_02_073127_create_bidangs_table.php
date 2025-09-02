<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('bidangs', function (Blueprint $table) {
$table->id();
$table->string('slug')->unique(); // contoh: bapakamar, bk_keamanan, dll
$table->string('name');
$table->string('icon')->nullable();
$table->string('color')->nullable();
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('bidangs'); }
};