<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->unsigned();
            $table->integer('stock')->unsigned()->default(0);
            $table->string('imagen')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'agotado'])->default('activo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};