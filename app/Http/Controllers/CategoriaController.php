<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'boolean'
        ]);

        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->estado = $request->estado ?? true;

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('categorias', 'public');
            $categoria->imagen = $imagenPath;
        }

        $categoria->save();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.show', compact('categoria'));
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'boolean'
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->estado = $request->estado ?? true;

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($categoria->imagen) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            
            $imagenPath = $request->file('imagen')->store('categorias', 'public');
            $categoria->imagen = $imagenPath;
        }

        $categoria->save();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($categoria->imagen) {
            Storage::disk('public')->delete($categoria->imagen);
        }
        
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}