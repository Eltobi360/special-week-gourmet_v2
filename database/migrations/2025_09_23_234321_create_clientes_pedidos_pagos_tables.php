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
        Schema::create('tipos_documento_identidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo')->nullable();
            $table->foreignId('tipo_documento_id')->nullable()->constrained('tipos_documento_identidad');
            $table->string('numero_documento', 20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('telefono', 50)->nullable();
            $table->string('direccion')->nullable();
            $table->unique(['tipo_documento_id', 'numero_documento']);
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('referencia_pedido', 50)->nullable()->unique();
            $table->foreignId('mesa_id')->nullable()->constrained();
            $table->foreignId('cliente_id')->nullable()->constrained();
            $table->foreignId('empleado_id')->constrained('users');
            $table->enum('tipo_pedido', ['mesa', 'delivery']);
            $table->dateTime('fecha_hora');
            $table->enum('estado_pedido', ['pendiente', 'en_preparacion', 'listo', 'servido', 'pagado', 'cancelado']);
            $table->decimal('monto_total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->enum('tipo_comprobante', ['boleta', 'factura'])->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained();
            $table->foreignId('plato_id')->constrained();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->enum('estado_comanda', ['pendiente', 'en_preparacion', 'listo', 'servido']);
            $table->string('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cajero_id')->constrained('users');
            $table->dateTime('fecha_apertura');
            $table->dateTime('fecha_cierre')->nullable();
            $table->decimal('saldo_inicial', 10, 2);
            $table->decimal('saldo_final', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('tipos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained();
            $table->foreignId('caja_id')->constrained();
            $table->foreignId('tipo_pago_id')->constrained('tipos_pago');
            $table->decimal('monto', 10, 2);
            $table->string('referencia_transaccion', 100)->nullable();
            $table->dateTime('fecha_pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('tipos_pago');
        Schema::dropIfExists('cajas');
        Schema::dropIfExists('detalle_pedidos');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('tipos_documento_identidad');
    }
};
