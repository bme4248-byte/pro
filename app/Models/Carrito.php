<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carritos';

    protected $fillable = [
        'usuario_id',
        'producto_id',
        'cantidad'
    ];

    protected $casts = [
        'cantidad' => 'integer'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Calcular subtotal del item
    public function getSubtotalAttribute()
    {
        return $this->cantidad * $this->producto->precio;
    }

    // Verificar si hay stock disponible
    public function hayStockDisponible()
    {
        return $this->producto->stock >= $this->cantidad;
    }

    // Incrementar cantidad
    public function incrementarCantidad($cantidad = 1)
    {
        $nuevaCantidad = $this->cantidad + $cantidad;
        
        if ($this->producto->stock >= $nuevaCantidad) {
            $this->cantidad = $nuevaCantidad;
            $this->save();
            return true;
        }
        
        return false;
    }

    // Decrementar cantidad
    public function decrementarCantidad($cantidad = 1)
    {
        $nuevaCantidad = $this->cantidad - $cantidad;
        
        if ($nuevaCantidad > 0) {
            $this->cantidad = $nuevaCantidad;
            $this->save();
            return true;
        } else {
            $this->delete();
            return true;
        }
    }

    // Scope para items de un usuario
    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    // Scope con información de producto
    public function scopeConProducto($query)
    {
        return $query->with(['producto.categoria', 'producto.vendedor']);
    }
}