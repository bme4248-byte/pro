<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Usuario;
use App\Models\DetallePedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['comprador', 'detalles'])->get();
        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $compradores = Usuario::where('tipo_usuario', 'comprador')->where('estado', true)->get();
        return view('pedidos.create', compact('compradores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'comprador_id' => 'required|exists:usuarios,id',
            'direccion_entrega' => 'required|string|max:500',
            'metodo_pago' => 'required|in:tarjeta,transferencia,qr,efectivo',
            'estado_pago' => 'required|in:pendiente,pagado,fallido'
        ]);

        $pedido = new Pedido();
        $pedido->comprador_id = $request->comprador_id;
        $pedido->numero_pedido = Pedido::generarNumeroPedido();
        $pedido->estado = 'pendiente';
        $pedido->total = 0; // Se calculará con los detalles
        $pedido->direccion_entrega = $request->direccion_entrega;
        $pedido->metodo_pago = $request->metodo_pago;
        $pedido->estado_pago = $request->estado_pago;

        $pedido->save();

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido creado exitosamente. Número: ' . $pedido->numero_pedido);
    }

    public function show($id)
    {
        $pedido = Pedido::with(['comprador', 'detalles.producto', 'detalles.vendedor'])->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }

    public function edit($id)
    {
        $pedido = Pedido::findOrFail($id);
        $compradores = Usuario::where('tipo_usuario', 'comprador')->where('estado', true)->get();
        
        return view('pedidos.edit', compact('pedido', 'compradores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comprador_id' => 'required|exists:usuarios,id',
            'direccion_entrega' => 'required|string|max:500',
            'metodo_pago' => 'required|in:tarjeta,transferencia,qr,efectivo',
            'estado' => 'required|in:pendiente,confirmado,enviado,entregado,cancelado',
            'estado_pago' => 'required|in:pendiente,pagado,fallido'
        ]);

        $pedido = Pedido::findOrFail($id);
        
        // Si el pedido se cancela, restaurar stock de productos
        if ($request->estado == 'cancelado' && $pedido->estado != 'cancelado') {
            $this->restaurarStock($pedido);
        }

        $pedido->comprador_id = $request->comprador_id;
        $pedido->direccion_entrega = $request->direccion_entrega;
        $pedido->metodo_pago = $request->metodo_pago;
        $pedido->estado = $request->estado;
        $pedido->estado_pago = $request->estado_pago;

        // Recalcular total si hay cambios en los detalles
        if ($pedido->isDirty()) {
            $pedido->total = $pedido->calcularTotal();
        }

        $pedido->save();

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        
        // Restaurar stock antes de eliminar
        $this->restaurarStock($pedido);
        
        $pedido->delete();

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido eliminado exitosamente.');
    }

    // Método para restaurar stock cuando se cancela un pedido
    private function restaurarStock(Pedido $pedido)
    {
        foreach ($pedido->detalles as $detalle) {
            $producto = $detalle->producto;
            if ($producto) {
                $producto->stock += $detalle->cantidad;
                $producto->actualizarEstado();
                $producto->save();
            }
        }
    }

    // Método para cambiar estado del pedido
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,enviado,entregado,cancelado'
        ]);

        $pedido = Pedido::findOrFail($id);
        
        if ($request->estado == 'cancelado' && $pedido->estado != 'cancelado') {
            $this->restaurarStock($pedido);
        }

        $pedido->estado = $request->estado;
        $pedido->save();

        return redirect()->route('pedidos.show', $pedido->id)
            ->with('success', 'Estado del pedido actualizado exitosamente.');
    }
}