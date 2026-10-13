<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orden_id')
                ->constrained('ordenes')
                ->cascadeOnDelete();

            $table->foreignId('platillo_id')
                ->constrained('platillos')
                ->restrictOnDelete();

            $table->unsignedInteger('cantidad');

            $table->decimal('precio', 10, 2);

            $table->string('notas', 500)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_detalles');
    }
};