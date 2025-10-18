<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprador_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('vendedor_id')->constrained('usuarios')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['comprador_id', 'vendedor_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('seguimientos');
    }
};