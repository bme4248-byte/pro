<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversacion_id')->constrained('conversaciones')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->text('mensaje');
            $table->enum('tipo', ['texto', 'imagen', 'archivo'])->default('texto');
            $table->string('archivo_url')->nullable();
            $table->boolean('leido')->default(false);
            $table->timestamp('leido_at')->nullable();
            $table->timestamps();
            
            $table->index(['conversacion_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensajes');
    }
};