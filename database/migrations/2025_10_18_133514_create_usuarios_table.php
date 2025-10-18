<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telefono', 20)->nullable();
            $table->text('direccion')->nullable();
            $table->enum('tipo_usuario', ['comprador', 'vendedor', 'admin'])->default('comprador');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->timestamp('ultima_conexion')->nullable();
            $table->string('foto_perfil')->nullable();
            $table->boolean('online')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};