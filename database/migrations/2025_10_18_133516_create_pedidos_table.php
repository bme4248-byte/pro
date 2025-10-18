<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprador_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('numero_pedido')->unique();
            $table->enum('estado', ['pendiente', 'confirmado', 'enviado', 'entregado', 'cancelado'])->default('pendiente');
            $table->decimal('total', 10, 2)->unsigned();
            $table->text('direccion_entrega');
            $table->enum('metodo_pago', ['tarjeta', 'transferencia', 'qr', 'efectivo']);
            $table->enum('estado_pago', ['pendiente', 'pagado', 'fallido'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};