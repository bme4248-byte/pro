<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->enum('tipo', ['mensaje', 'pedido', 'sistema', 'producto']);
            $table->string('titulo');
            $table->text('mensaje');
            $table->json('data')->nullable();
            $table->boolean('leido')->default(false);
            $table->timestamp('leido_at')->nullable();
            $table->timestamps();
            
            $table->index(['usuario_id', 'leido']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notificaciones');
    }
};