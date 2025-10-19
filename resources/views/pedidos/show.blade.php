@extends('template')

@section('title', 'Ver Pedido')

@push('css')
    <style>
        .badge-estado {
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .estado-pendiente { background-color: #fef3c7; color: #92400e; }
        .estado-confirmado { background-color: #dbeafe; color: #1e40af; }
        .estado-enviado { background-color: #f3e8ff; color: #7e22ce; }
        .estado-entregado { background-color: #d1fae5; color: #065f46; }
        .estado-cancelado { background-color: #fee2e2; color: #991b1b; }
        
        .badge-pago {
            padding: 0.4rem 0.6rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .pago-pendiente { background-color: #fef3c7; color: #92400e; }
        .pago-pagado { background-color: #d1fae5; color: #065f46; }
        .pago-fallido { background-color: #fee2e2; color: #991b1b; }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detalles del Pedido</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
        <li class="breadcrumb-item active">Detalles</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-info-circle me-1"></i>
                    Información del Pedido: {{ $pedido->numero_pedido }}
                </div>
                <div>
                    @php
                        $estadoClass = 'estado-' . $pedido->estado;
                        $pagoClass = 'pago-' . $pedido->estado_pago;
                    @endphp
                    <span class="badge-estado {{ $estadoClass }} me-2">
                        {{ ucfirst($pedido->estado) }}
                    </span>
                    <span class="badge-pago {{ $pagoClass }}">
                        Pago: {{ ucfirst($pedido->estado_pago) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Información del Pedido -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Información General</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">N° Pedido:</th>
                            <td>{{ $pedido->numero_pedido }}</td>
                        </tr>
                        <tr>
                            <th>Comprador:</th>
                            <td>{{ $pedido->comprador->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $pedido->comprador->email }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td>{{ $pedido->comprador->telefono ?: 'No especificado' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Detalles de Entrega y Pago</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Método de Pago:</th>
                            <td>{{ ucfirst($pedido->metodo_pago) }}</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td class="fw-bold text-success">${{ number_format($pedido->total, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación:</th>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $pedido->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Dirección de Entrega -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5>Dirección de Entrega</h5>
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">{{ $pedido->direccion_entrega }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del Pedido -->
            <div class="row">
                <div class="col-12">
                    <h5>Productos del Pedido</h5>
                    @if($pedido->detalles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Vendedor</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->detalles as $detalle)
                                    <tr>
                                        <td>
                                            <strong>{{ $detalle->producto->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">Categoría: {{ $detalle->producto->categoria->nombre }}</small>
                                        </td>
                                        <td>{{ $detalle->vendedor->name }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                        <td class="fw-bold text-success">${{ number_format($detalle->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total:</td>
                                        <td class="fw-bold text-success">${{ number_format($pedido->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Este pedido no tiene productos agregados. 
                            <a href="{{ route('detalle-pedidos.create') }}" class="alert-link">