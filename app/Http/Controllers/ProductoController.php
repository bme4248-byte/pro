<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['categoria', 'vendedor'])->get();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::where('estado', true)->get();
        $vendedores = Usuario::where('tipo_usuario', 'vendedor')->where('estado', true)->get();
        return view('productos.create', compact('categorias', 'vendedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendedor_id' => 'required|exists:usuarios,id',
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:activo,inactivo,agotado'
        ]);

        $producto = new Producto();
        $producto->vendedor_id = $request->vendedor_id;
        $producto->categoria_id = $request->categoria_id;
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->estado = $request->estado;

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $imagenPath;
        }

        $producto->save();

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show($id)
    {
        $producto = Producto::with(['categoria', 'vendedor'])->findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('estado', true)->get();
        $vendedores = Usuario::where('tipo_usuario', 'vendedor')->where('estado', true)->get();
        return view('productos.edit', compact('producto', 'categorias', 'vendedores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vendedor_id' => 'required|exists:usuarios,id',
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:activo,inactivo,agotado'
        ]);

        $producto = Producto::findOrFail($id);
        $producto->vendedor_id = $request->vendedor_id;
        $producto->categoria_id = $request->categoria_id;
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->estado = $request->estado;

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $imagenPath;
        }

        $producto->save();

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}