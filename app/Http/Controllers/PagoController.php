<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['pedido.comprador'])->get();
        return view('pagos.index', compact('pagos'));
    }

    public function create()
    {
        $pedidos = Pedido::where('estado_pago', '!=', 'pagado')->get();
        return view('pagos.create', compact('pedidos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:50',
            'estado' => 'required|in:pendiente,completado,fallido,reembolsado',
            'codigo_transaccion' => 'nullable|string|max:100',
            'fecha_pago' => 'nullable|date'
        ]);

        // Verificar que el pedido no tenga ya un pago completado
        $pagoExistente = Pago::where('pedido_id', $request->pedido_id)
                            ->where('estado', 'completado')
                            ->first();
        
        if ($pagoExistente && $request->estado == 'completado') {
            return back()->withErrors(['pedido_id' => 'Este pedido ya tiene un pago completado.']);
        }

        $pago = new Pago();
        $pago->pedido_id = $request->pedido_id;
        $pago->monto = $request->monto;
        $pago->metodo_pago = $request->metodo_pago;
        $pago->estado = $request->estado;
        $pago->codigo_transaccion = $request->codigo_transaccion;

        if ($request->fecha_pago) {
            $pago->fecha_pago = $request->fecha_pago;
        }

        // Si el estado es completado y no hay fecha, usar fecha actual
        if ($request->estado == 'completado' && !$request->fecha_pago) {
            $pago->fecha_pago = now();
        }

        // Generar código de transacción si no se proporciona y está completado
        if ($request->estado == 'completado' && !$request->codigo_transaccion) {
            $pago->codigo_transaccion = Pago::generarCodigoTransaccion();
        }

        $pago->save();

        // Actualizar estado del pago en el pedido
        $this->actualizarEstadoPedido($pago);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago creado exitosamente.');
    }

    public function show($id)
    {
        $pago = Pago::with(['pedido.comprador', 'pedido.detalles'])->findOrFail($id);
        return view('pagos.show', compact('pago'));
    }

    public function edit($id)
    {
        $pago = Pago::findOrFail($id);
        $pedidos = Pedido::all();
        
        return view('pagos.edit', compact('pago', 'pedidos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:50',
            'estado' => 'required|in:pendiente,completado,fallido,reembolsado',
            'codigo_transaccion' => 'nullable|string|max:100',
            'fecha_pago' => 'nullable|date'
        ]);

        $pago = Pago::findOrFail($id);

        // Verificar que no haya otro pago completado para el mismo pedido
        if ($request->estado == 'completado') {
            $pagoExistente = Pago::where('pedido_id', $request->pedido_id)
                                ->where('estado', 'completado')
                                ->where('id', '!=', $id)
                                ->first();
            
            if ($pagoExistente) {
                return back()->withErrors(['pedido_id' => 'Este pedido ya tiene otro pago completado.']);
            }
        }

        $pago->pedido_id = $request->pedido_id;
        $pago->monto = $request->monto;
        $pago->metodo_pago = $request->metodo_pago;
        $pago->estado = $request->estado;
        $pago->codigo_transaccion = $request->codigo_transaccion;

        if ($request->fecha_pago) {
            $pago->fecha_pago = $request->fecha_pago;
        }

        // Si el estado cambia a completado y no hay fecha, usar fecha actual
        if ($request->estado == 'completado' && !$pago->fecha_pago) {
            $pago->fecha_pago = now();
        }

        // Generar código de transacción si no se proporciona y está completado
        if ($request->estado == 'completado' && !$request->codigo_transaccion && !$pago->codigo_transaccion) {
            $pago->codigo_transaccion = Pago::generarCodigoTransaccion();
        }

        $pago->save();

        // Actualizar estado del pago en el pedido
        $this->actualizarEstadoPedido($pago);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        
        // Si el pago estaba completado, actualizar el estado del pedido
        if ($pago->estaCompletado()) {
            $pago->pedido->estado_pago = 'pendiente';
            $pago->pedido->save();
        }
        
        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado exitosamente.');
    }

    // Método para cambiar estado del pago
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,completado,fallido,reembolsado'
        ]);

        $pago = Pago::findOrFail($id);

        switch ($request->estado) {
            case 'completado':
                $pago->marcarComoCompletado();
                break;
            case 'fallido':
                $pago->marcarComoFallido();
                break;
            case 'reembolsado':
                if ($pago->puedeSerReembolsado()) {
                    $pago->reembolsar();
                } else {
                    return back()->withErrors(['estado' => 'No se puede reembolsar un pago que no está completado.']);
                }
                break;
            default:
                $pago->estado = $request->estado;
                $pago->save();
                break;
        }

        return redirect()->route('pagos.show', $pago->id)
            ->with('success', 'Estado del pago actualizado exitosamente.');
    }

    // Método auxiliar para actualizar estado del pedido
    private function actualizarEstadoPedido(Pago $pago)
    {
        $pedido = $pago->pedido;
        
        if ($pago->estaCompletado()) {
            $pedido->estado_pago = 'pagado';
        } elseif ($pago->estado == 'fallido') {
            $pedido->estado_pago = 'fallido';
        } elseif ($pago->estado == 'reembolsado') {
            $pedido->estado_pago = 'pendiente';
        } else {
            $pedido->estado_pago = 'pendiente';
        }
        
        $pedido->save();
    }
}