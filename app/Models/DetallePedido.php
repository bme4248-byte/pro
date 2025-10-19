<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedidos';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'vendedor_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'cantidad' => 'integer'
    ];

    // Relación con pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación con vendedor
    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
    }

    // Calcular subtotal automáticamente
    public function calcularSubtotal()
    {
        return $this->cantidad * $this->precio_unitario;
    }

    // Scope para detalles de un pedido específico
    public function scopePorPedido($query, $pedidoId)
    {
        return $query->where('pedido_id', $pedidoId);
    }

    // Scope para detalles de un vendedor específico
    public function scopePorVendedor($query, $vendedorId)
    {
        return $query->where('vendedor_id', $vendedorId);
    }
}