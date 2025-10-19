@extends('template')

@section('title', 'Crear Categoría')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Crear Nueva Categoría</h1>
    <ol class="breadcrumb mb-4">
        
        <li class="breadcrumb-item"><a href="{{ route('categorias.index') }}">Categorías</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Formulario de Nueva Categoría
        </div>
        <div class="card-body">
            <form action="{{ route('categorias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Categoría *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                   id="imagen" name="imagen" accept="image/*">
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Formatos permitidos: JPEG, PNG, JPG, GIF. Tamaño máximo: 2MB</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="estado" name="estado" value="1" checked>
                            <label class="form-check-label" for="estado">Categoría Activa</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="5">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection