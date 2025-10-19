@extends('template')

@section('title', 'Ver Pago')

@push('css')
    <style>
        .badge-estado {
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .estado-pendiente { background-color: #fef3c7; color: #92400e; }
        .estado-completado { background-color: #d1fae5; color: #065f46; }
        .estado-fallido { background-color: #fee2e2; color: #991b1b; }
        .estado-reembolsado { background-color: #e0e7ff; color: #3730a3; }
        
        .codigo-transaccion {
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 0.5rem;
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detalles del Pago</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}">Pagos</a></li>
        <li class="breadcrumb-item active">Detalles</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-info-circle me-1"></i>
                    Información del Pago: {{ $pago->id }}
                </div>
                <div>
                    @php
                        $estadoClass = 'estado-' . $pago->estado;
                    @endphp
                    <span class="badge-estado {{ $estadoClass }}">
                        {{ ucfirst($pago->estado) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Información del Pago -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Información del Pago</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">ID:</th>
                            <td>{{ $pago->id }}</td>
                        </tr>
                        <tr>
                            <th>Monto:</th>
                            <td class="fw-bold text-success">${{ number_format($pago->monto, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Método de Pago:</th>
                            <td>{{ ucfirst($pago->metodo_pago) }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                <span class="badge-estado {{ $estadoClass }}">
                                    {{ ucfirst($pago->estado) }}
                                </span>
                            </td