@extends('template')

@section('title', 'Gestión de Pedidos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <style>
        .badge-estado {
            padding: 0.4rem 0.6rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .estado-pendiente { background-color: #fef3c7; color: #92400e; }
        .estado-confirmado { background-color: #dbeafe; color: #1e40af; }
        .estado-enviado { background-color: #f3e8ff; color: #7e22ce; }
        .estado-entregado { background-color: #d1fae5; color: #065f46; }
        .estado-cancelado { background-color: #fee2e2; color: #991b1b; }
        
        .badge-pago {
            padding: 0.3rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 500;
        }
        .pago-pendiente { background-color: #fef3c7; color: #92400e; }
        .pago-pagado { background-color: #d1fae5; color: #065f46; }
        .pago-fallido { background-color: #fee2e2; color: #991b1b; }
        
        .price {
            font-weight: bold;
            color: #059669;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Pedidos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Pedidos</li>
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
                    Lista de Pedidos
                </div>
                <a href="{{ route('pedidos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    Nuevo Pedido
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>N° Pedido</th>
                        <th>Comprador</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Pago</th>
                        <th>Método Pago</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    <tr>
                        <td>
                            <strong>{{ $pedido->numero_pedido }}</strong>
                        </td>
                        <td>{{ $pedido->comprador->name }}</td>
                        <td class="price">${{ number_format($pedido->total, 2) }}</td>
                        <td>
                            @php
                                $estadoClass = 'estado-' . $pedido->estado;
                            @endphp
                            <span class="badge-estado {{ $estadoClass }}">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $pagoClass = 'pago-' . $pedido->estado_pago;
                            @endphp
                            <span class="badge-pago {{ $pagoClass }}">
                                {{ ucfirst($pedido->estado_pago) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ ucfirst($pedido->metodo_pago) }}</small>
                        </td>
                        <td>{{ $pedido->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este pedido?')">
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