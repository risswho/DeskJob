
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('nama');
            $table->text('kendala')->nullable();
            $table->date('tanggal');

            $table->timestamps();

            $table->index('tanggal');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
