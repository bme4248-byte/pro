<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'vendedor_id',
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'estado'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer'
    ];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación con vendedor (usuario)
    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
    }

    // Relación con carritos
    public function carritos()
    {
        return $this->hasMany(Carrito::class);
    }

    // Relación con detalle de pedidos
    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    // Scope para productos activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // Scope para productos con stock
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Método para verificar si está agotado
    public function estaAgotado()
    {
        return $this->stock == 0;
    }

    // Método para actualizar estado basado en stock
    public function actualizarEstado()
    {
        if ($this->stock == 0) {
            $this->estado = 'agotado';
        } elseif ($this->estado == 'agotado' && $this->stock > 0) {
            $this->estado = 'activo';
        }
        $this->save();
    }
}