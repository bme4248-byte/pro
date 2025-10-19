@extends('template')

@section('title', 'Gestión de Pagos')

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
        .estado-completado { background-color: #d1fae5; color: #065f46; }
        .estado-fallido { background-color: #fee2e2; color: #991b1b; }
        .estado-reembolsado { background-color: #e0e7ff; color: #3730a3; }
        
        .price {
            font-weight: bold;
            color: #059669;
        }
        .codigo-transaccion {
            font-family: monospace;
            font-size: 0.875rem;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Pagos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Pagos</li>
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
                    Lista de Pagos
                </div>
                <a href="{{ route('pagos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    Nuevo Pago
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pedido</th>
                        <th>Monto</th>
                        <th>Método Pago</th>
                        <th>Estado</th>
                        <th>Código Transacción</th>
                        <th>Fecha Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                    <tr>
                        <td>{{ $pago->id }}</td>
                        <td>
                            <strong>{{ $pago->pedido->numero_pedido }}</strong>
                            <br>
                            <small class="text-muted">{{ $pago->pedido->comprador->name }}</small>
                        </td>
                        <td class="price">${{ number_format($pago->monto, 2) }}</td>
                        <td>{{ ucfirst($pago->metodo_pago) }}</td>
                        <td>
                            @php
                                $estadoClass = 'estado-' . $pago->estado;
                            @endphp
                            <span class="badge-estado {{ $estadoClass }}">
                                {{ ucfirst($pago->estado) }}
                            </span>
                        </td>
                        <td>
                            @if($pago->codigo_transaccion)
                                <span class="codigo-transaccion">{{ $pago->codigo_transaccion }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($pago->fecha_pago)
                                {{ $pago->fecha_pago->format('d/m/Y H:i') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('pagos.show', $pago->id) }}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pagos.edit', $pago->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pagos.destroy', $pago->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este pago?')">
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