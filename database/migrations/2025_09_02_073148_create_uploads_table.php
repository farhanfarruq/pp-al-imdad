<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('uploads', function (Blueprint $table) {
$table->id();
$table->foreignId('report_id')->constrained()->cascadeOnDelete();
$table->string('path');
$table->string('mime');
$table->integer('size');
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('uploads'); }
};