<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    // Usuario temporal por defecto (puedes cambiar el ID)
    private function getUsuarioId()
    {
        return Auth::id() ?? 1; // Usuario 1 por defecto si no hay login
    }
    
    public function index()
    {
        $carritoItems = Carrito::conProducto()
            ->porUsuario($this->getUsuarioId())
            ->get();
            
        $total = $carritoItems->sum(function ($item) {
            return $item->subtotal;
        });

        return view('carrito.index', compact('carritoItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Verificar stock
        if ($producto->stock < $request->cantidad) {
            return back()->withErrors(['cantidad' => 'Stock insuficiente. Stock disponible: ' . $producto->stock]);
        }

        // Verificar si el producto ya está en el carrito
        $carritoItem = Carrito::where('usuario_id', $this->getUsuarioId())
            ->where('producto_id', $request->producto_id)
            ->first();

        if ($carritoItem) {
            // Actualizar cantidad si ya existe
            $nuevaCantidad = $carritoItem->cantidad + $request->cantidad;
            
            if ($producto->stock < $nuevaCantidad) {
                return back()->withErrors(['cantidad' => 'Stock insuficiente. Stock disponible: ' . $producto->stock]);
            }
            
            $carritoItem->cantidad = $nuevaCantidad;
            $carritoItem->save();
        } else {
            // Crear nuevo item
            Carrito::create([
                'usuario_id' => $this->getUsuarioId(),
                'producto_id' => $request->producto_id,
                'cantidad' => $request->cantidad
            ]);
        }

        return redirect()->route('carrito.index')
            ->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $carritoItem = Carrito::where('usuario_id', $this->getUsuarioId())
            ->findOrFail($id);

        $producto = $carritoItem->producto;

        if ($producto->stock < $request->cantidad) {
            return back()->withErrors(['cantidad' => 'Stock insuficiente. Stock disponible: ' . $producto->stock]);
        }

        $carritoItem->cantidad = $request->cantidad;
        $carritoItem->save();

        return redirect()->route('carrito.index')
            ->with('success', 'Carrito actualizado.');
    }

    public function destroy($id)
    {
        $carritoItem = Carrito::where('usuario_id', $this->getUsuarioId())
            ->findOrFail($id);
            
        $carritoItem->delete();

        return redirect()->route('carrito.index')
            ->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        Carrito::where('usuario_id', $this->getUsuarioId())->delete();

        return redirect()->route('carrito.index')
            ->with('success', 'Carrito vaciado.');
    }

    public function incrementar($id)
    {
        $carritoItem = Carrito::where('usuario_id', $this->getUsuarioId())
            ->findOrFail($id);

        if ($carritoItem->incrementarCantidad()) {
            return redirect()->route('carrito.index')
                ->with('success', 'Cantidad actualizada.');
        } else {
            return back()->withErrors(['cantidad' => 'No hay suficiente stock.']);
        }
    }

    public function decrementar($id)
    {
        $carritoItem = Carrito::where('usuario_id', $this->getUsuarioId())
            ->findOrFail($id);

        $carritoItem->decrementarCantidad();

        return redirect()->route('carrito.index')
            ->with('success', 'Cantidad actualizada.');
    }

    /**
     * Obtener el contador de items en el carrito para el menú
     */
    public function obtenerContador()
    {
        try {
            $contador = Carrito::where('usuario_id', $this->getUsuarioId())->count();
            return response()->json(['contador' => $contador]);
        } catch (\Exception $e) {
            return response()->json(['contador' => 0]);
        }
    }
}