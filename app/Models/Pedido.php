<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'comprador_id',
        'numero_pedido',
        'estado',
        'total',
        'direccion_entrega',
        'metodo_pago',
        'estado_pago'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relación con comprador (usuario)
    public function comprador()
    {
        return $this->belongsTo(Usuario::class, 'comprador_id');
    }

    // Relación con detalles de pedido
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    // Relación con pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    // Generar número de pedido único
    public static function generarNumeroPedido()
    {
        $prefix = 'PED';
        $date = now()->format('Ymd');
        $lastOrder = self::where('numero_pedido', 'like', $prefix . $date . '%')->latest()->first();
        
        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->numero_pedido, -4));
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        
        return $prefix . $date . $nextNumber;
    }

    // Calcular total del pedido
    public function calcularTotal()
    {
        return $this->detalles->sum('subtotal');
    }

    // Scope para pedidos pendientes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    // Scope para pedidos de un comprador
    public function scopePorComprador($query, $compradorId)
    {
        return $query->where('comprador_id', $compradorId);
    }

    // Método para verificar si puede ser cancelado
    public function puedeSerCancelado()
    {
        return in_array($this->estado, ['pendiente', 'confirmado']);
    }

    // Método para actualizar estado
    public function actualizarEstado($nuevoEstado)
    {
        $estadosValidos = ['pendiente', 'confirmado', 'enviado', 'entregado', 'cancelado'];
        
        if (in_array($nuevoEstado, $estadosValidos)) {
            $this->estado = $nuevoEstado;
            $this->save();
            return true;
        }
        
        return false;
    }

    // Método para verificar si está pagado
    public function estaPagado()
    {
        return $this->estado_pago === 'pagado';
    }
}