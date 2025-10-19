@extends('template')

@section('title', 'Ver Categoría')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detalles de la Categoría</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categorias.index') }}">Categorías</a></li>
        <li class="breadcrumb-item active">Detalles</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Información de la Categoría
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if($categoria->imagen)
                        <img src="{{ asset('storage/' . $categoria->imagen) }}" alt="{{ $categoria->nombre }}" 
                             class="img-fluid rounded" style="max-height: 300px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        <div class="text-muted mt-2">Sin imagen</div>
                    @endif
                </div>
                
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID:</th>
                            <td>{{ $categoria->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $categoria->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Descripción:</th>
                            <td>{{ $categoria->descripcion ?: 'Sin descripción' }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                <span class="badge {{ $categoria->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $categoria->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación:</th>
                            <td>{{ $categoria->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $categoria->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-start mt-4">
                        <a href="{{ route('categorias.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver
                        </a>
                        <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>
                            Editar
                        </a>
                        <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                <i class="fas fa-trash me-1"></i>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection