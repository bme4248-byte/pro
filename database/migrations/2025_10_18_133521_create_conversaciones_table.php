<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conversaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprador_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('vendedor_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->onDelete('set null');
            $table->string('asunto')->nullable();
            $table->text('ultimo_mensaje')->nullable();
            $table->timestamp('ultimo_mensaje_at')->nullable();
            $table->boolean('leido_comprador')->default(true);
            $table->boolean('leido_vendedor')->default(true);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['comprador_id', 'vendedor_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversaciones');
    }
};