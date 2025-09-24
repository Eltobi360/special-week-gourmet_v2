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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->timestamps();
        });

        Schema::create('platos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->decimal('costo_produccion', 10, 2)->nullable();
            $table->string('url_imagen')->nullable();
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });

        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plato_id')->constrained()->unique()->onDelete('cascade');
            $table->text('instrucciones');
            $table->integer('tiempo_preparacion_min')->nullable();
            $table->timestamps();
        });

        Schema::create('receta_productos', function (Blueprint $table) {
            $table->foreignId('receta_id')->constrained();
            $table->foreignId('producto_id')->constrained();
            $table->decimal('cantidad_necesaria', 10, 2);
            $table->primary(['receta_id', 'producto_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receta_productos');
        Schema::dropIfExists('recetas');
        Schema::dropIfExists('platos');
        Schema::dropIfExists('categorias');
    }
};
