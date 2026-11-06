
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->foreignId('penanggung_jawab')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('nama');
            $table->date('target_selesai');
            $table->text('deskripsi');

            $table->string('status', 30)->default('belum_mulai');

            $table->timestamps();

            $table->index('status');
            $table->index('target_selesai');
            $table->index('penanggung_jawab');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
