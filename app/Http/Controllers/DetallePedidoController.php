<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DetallePedidoController extends Controller
{
    public function index()
    {
        $detallePedidos = DetallePedido::with(['pedido', 'producto', 'vendedor'])->get();
        return view('detalle_pedidos.index', compact('detallePedidos'));
    }

    public function create()
    {
        $pedidos = Pedido::all();
        $productos = Producto::where('estado', 'activo')->get();
        $vendedores = Usuario::where('tipo_usuario', 'vendedor')->where('estado', true)->get();
        
        return view('detalle_pedidos.create', compact('pedidos', 'productos', 'vendedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'producto_id' => 'required|exists:productos,id',
            'vendedor_id' => 'required|exists:usuarios,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0'
        ]);

        // Obtener el producto para validar stock
        $producto = Producto::findOrFail($request->producto_id);
        
        if ($producto->stock < $request->cantidad) {
            return back()->withErrors(['cantidad' => 'Stock insuficiente. Stock disponible: ' . $producto->stock]);
        }

        $detallePedido = new DetallePedido();
        $detallePedido->pedido_id = $request->pedido_id;
        $detallePedido->producto_id = $request->producto_id;
        $detallePedido->vendedor_id = $request->vendedor_id;
        $detallePedido->cantidad = $request->cantidad;
        $detallePedido->precio_unitario = $request->precio_unitario;
        $detallePedido->subtotal = $request->cantidad * $request->precio_unitario;

        $detallePedido->save();

        // Actualizar stock del producto
        $producto->stock -= $request->cantidad;
        $producto->actualizarEstado();
        $producto->save();

        return redirect()->route('detalle-pedidos.index')
            ->with('success', 'Detalle de pedido creado exitosamente.');
    }

    public function show($id)
    {
        $detallePedido = DetallePedido::with(['pedido', 'producto', 'vendedor'])->findOrFail($id);
        return view('detalle_pedidos.show', compact('detallePedido'));
    }

    public function edit($id)
    {
        $detallePedido = DetallePedido::findOrFail($id);
        $pedidos = Pedido::all();
        $productos = Producto::where('estado', 'activo')->get();
        $vendedores = Usuario::where('tipo_usuario', 'vendedor')->where('estado', true)->get();
        
        return view('detalle_pedidos.edit', compact('detallePedido', 'pedidos', 'productos', 'vendedores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'producto_id' => 'required|exists:productos,id',
            'vendedor_id' => 'required|exists:usuarios,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0'
        ]);

        $detallePedido = DetallePedido::findOrFail($id);
        $producto = Producto::findOrFail($request->producto_id);

        // Calcular diferencia de stock
        $diferenciaStock = $request->cantidad - $detallePedido->cantidad;

        if ($producto->stock < $diferenciaStock) {
            return back()->withErrors(['cantidad' => 'Stock insuficiente. Stock disponible: ' . $producto->stock]);
        }

        // Restaurar stock anterior
        $productoAnterior = Producto::find($detallePedido->producto_id);
        if ($productoAnterior) {
            $productoAnterior->stock += $detallePedido->cantidad;
            $productoAnterior->actualizarEstado();
            $productoAnterior->save();
        }

        $detallePedido->pedido_id = $request->pedido_id;
        $detallePedido->producto_id = $request->producto_id;
        $detallePedido->vendedor_id = $request->vendedor_id;
        $detallePedido->cantidad = $request->cantidad;
        $detallePedido->precio_unitario = $request->precio_unitario;
        $detallePedido->subtotal = $request->cantidad * $request->precio_unitario;

        $detallePedido->save();

        // Actualizar stock del nuevo producto
        $producto->stock -= $request->cantidad;
        $producto->actualizarEstado();
        $producto->save();

        return redirect()->route('detalle-pedidos.index')
            ->with('success', 'Detalle de pedido actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $detallePedido = DetallePedido::findOrFail($id);
        
        // Restaurar stock del producto
        $producto = Producto::find($detallePedido->producto_id);
        if ($producto) {
            $producto->stock += $detallePedido->cantidad;
            $producto->actualizarEstado();
            $producto->save();
        }
        
        $detallePedido->delete();

        return redirect()->route('detalle-pedidos.index')
            ->with('success', 'Detalle de pedido eliminado exitosamente.');
    }
}