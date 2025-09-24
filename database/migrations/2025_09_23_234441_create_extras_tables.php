<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained();
            $table->foreignId('mesa_id')->constrained();
            $table->date('fecha');
            $table->time('hora');
            $table->enum('estado', ['confirmada', 'cancelada', 'atendida']);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['descuento', 'combo']);
            $table->decimal('valor_descuento', 5, 2)->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->timestamps();
        });

        Schema::create('plato_promocion', function (Blueprint $table) {
            $table->foreignId('plato_id')->constrained();
            $table->foreignId('promocion_id')->constrained('promociones'); // Corrección: la tabla es 'promociones'
            $table->primary(['plato_id', 'promocion_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plato_promocion');
        Schema::dropIfExists('promociones');
        Schema::dropIfExists('reservas');
    }
};
