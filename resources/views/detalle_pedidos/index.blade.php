@extends('template')

@section('title', 'Gestión de Detalle de Pedidos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <style>
        .badge-pedido {
            background-color: #6c757d;
            color: white;
        }
        .price {
            font-weight: bold;
            color: #059669;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Detalle de Pedidos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Detalle de Pedidos</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Lista de Detalles de Pedidos
                </div>
                <a href="{{ route('detalle-pedidos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    Nuevo Detalle
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pedido</th>
                        <th>Producto</th>
                        <th>Vendedor</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detallePedidos as $detalle)
                    <tr>
                        <td>{{ $detalle->id }}</td>
                        <td>
                            <span class="badge badge-pedido">#{{ $detalle->pedido->numero_pedido }}</span>
                            <br>
                            <small class="text-muted">{{ $detalle->pedido->comprador->name }}</small>
                        </td>
                        <td>
                            <strong>{{ $detalle->producto->nombre }}</strong>
                            <br>
                            <small class="text-muted">Categoría: {{ $detalle->producto->categoria->nombre }}</small>
                        </td>
                        <td>{{ $detalle->vendedor->name }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td class="price">${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="price">${{ number_format($detalle->subtotal, 2) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('detalle-pedidos.show', $detalle->id) }}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('detalle-pedidos.edit', $detalle->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('detalle-pedidos.destroy', $detalle->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este detalle de pedido?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple);
            }
        });
    </script>
@endpush