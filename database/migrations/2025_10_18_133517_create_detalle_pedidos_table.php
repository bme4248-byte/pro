<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('vendedor_id')->constrained('usuarios')->onDelete('cascade');
            $table->integer('cantidad')->unsigned();
            $table->decimal('precio_unitario', 10, 2)->unsigned();
            $table->decimal('subtotal', 10, 2)->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};