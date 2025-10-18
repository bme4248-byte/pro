<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'name',
        'email', 
        'password',
        'telefono',
        'direccion',
        'tipo_usuario',
        'estado',
        'foto_perfil',
        'online'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ultima_conexion' => 'datetime',
        'online' => 'boolean',
        'estado' => 'integer'
    ];
}