<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orden_id')
                ->constrained('ordenes')
                ->cascadeOnDelete();

            $table->enum('metodo', [
                'efectivo',
                'tarjeta'
            ]);

            $table->decimal('monto', 10, 2);

            $table->enum('estado', [
                'pendiente',
                'pagado',
                'cancelado'
            ])->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};