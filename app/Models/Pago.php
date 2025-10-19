<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'pedido_id',
        'monto',
        'metodo_pago',
        'estado',
        'codigo_transaccion',
        'fecha_pago'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relación con pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    // Generar código de transacción único
    public static function generarCodigoTransaccion()
    {
        $prefix = 'TXN';
        $date = now()->format('YmdHis');
        $random = strtoupper(substr(uniqid(), -6));
        
        return $prefix . $date . $random;
    }

    // Scope para pagos completados
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    // Scope para pagos pendientes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    // Scope para pagos fallidos
    public function scopeFallidos($query)
    {
        return $query->where('estado', 'fallido');
    }

    // Método para marcar como completado
    public function marcarComoCompletado($codigoTransaccion = null)
    {
        $this->estado = 'completado';
        $this->fecha_pago = now();
        
        if ($codigoTransaccion) {
            $this->codigo_transaccion = $codigoTransaccion;
        } elseif (!$this->codigo_transaccion) {
            $this->codigo_transaccion = self::generarCodigoTransaccion();
        }
        
        $this->save();

        // Actualizar estado del pago en el pedido
        $this->pedido->estado_pago = 'pagado';
        $this->pedido->save();
    }

    // Método para marcar como fallido
    public function marcarComoFallido()
    {
        $this->estado = 'fallido';
        $this->save();

        // Actualizar estado del pago en el pedido
        $this->pedido->estado_pago = 'fallido';
        $this->pedido->save();
    }

    // Método para reembolsar
    public function reembolsar()
    {
        if ($this->estado === 'completado') {
            $this->estado = 'reembolsado';
            $this->save();

            // Actualizar estado del pago en el pedido
            $this->pedido->estado_pago = 'pendiente';
            $this->pedido->save();
            
            return true;
        }
        
        return false;
    }

    // Verificar si está completado
    public function estaCompletado()
    {
        return $this->estado === 'completado';
    }

    // Verificar si puede ser reembolsado
    public function puedeSerReembolsado()
    {
        return $this->estado === 'completado';
    }
}