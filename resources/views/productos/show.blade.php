@extends('template')

@section('title', 'Ver Producto')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detalles del Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
        <li class="breadcrumb-item active">Detalles</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Información del Producto
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" 
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
                            <td>{{ $producto->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $producto->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Descripción:</th>
                            <td>{{ $producto->descripcion ?: 'Sin descripción' }}</td>
                        </tr>
                        <tr>
                            <th>Categoría:</th>
                            <td>{{ $producto->categoria->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Vendedor:</th>
                            <td>{{ $producto->vendedor->name }} ({{ $producto->vendedor->email }})</td>
                        </tr>
                        <tr>
                            <th>Precio:</th>
                            <td class="fw-bold text-success">${{ number_format($producto->precio, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Stock:</th>
                            <td>{{ $producto->stock }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'activo' => 'bg-success',
                                        'inactivo' => 'bg-warning',
                                        'agotado' => 'bg-danger'
                                    ][$producto->estado];
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($producto->estado) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación:</th>
                            <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $producto->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-start mt-4">
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver
                        </a>
                        <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>
                            Editar
                        </a>
                        <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
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