@extends('template')

@section('title', 'Ver Detalle de Pedido')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detalles del Pedido</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('detalle-pedidos.index') }}">Detalle de Pedidos</a></li>
        <li class="breadcrumb-item active">Detalles</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Información del Detalle de Pedido
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">ID:</th>
                            <td>{{ $detallePedido->id }}</td>
                        </tr>
                        <tr>
                            <th>Pedido:</th>
                            <td>
                                <span class="badge bg-secondary">#{{ $detallePedido->pedido->numero_pedido }}</span>
                                <br>
                                <small>Comprador: {{ $detallePedido->pedido->comprador->name }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Producto:</th>
                            <td>
                                <strong>{{ $detallePedido->producto->nombre }}</strong>
                                <br>
                                <small>Categoría: {{ $detallePedido->producto->categoria->nombre }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Vendedor:</th>
                            <td>{{ $detallePedido->vendedor->name }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Cantidad:</th>
                            <td>{{ $detallePedido->cantidad }}</td>
                        </tr>
                        <tr>
                            <th>Precio Unitario:</th>
                            <td class="fw-bold text-success">${{ number_format($detallePedido->precio_unitario, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Subtotal:</th>
                            <td class="fw-bold text-success">${{ number_format($detallePedido->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación:</th>
                            <td>{{ $detallePedido->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $detallePedido->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-start mt-4">
                <a href="{{ route('detalle-pedidos.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>
                    Volver
                </a>
                <a href="{{ route('detalle-pedidos.edit', $detallePedido->id) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>
                    Editar
                </a>
                <form action="{{ route('detalle-pedidos.destroy', $detallePedido->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este detalle de pedido?')">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection