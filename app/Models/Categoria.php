<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    // Relación con productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}