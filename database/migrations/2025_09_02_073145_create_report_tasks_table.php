<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('report_tasks', function (Blueprint $table) {
$table->id();
$table->foreignId('report_id')->constrained()->cascadeOnDelete();
$table->foreignId('jobdesk_id')->constrained()->cascadeOnDelete();
$table->boolean('done')->default(false);
$table->text('alasan')->nullable();
$table->text('solusi')->nullable();
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('report_tasks'); }
};