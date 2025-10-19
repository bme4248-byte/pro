@extends('template')

@section('title', 'Mi Carrito de Compras')

@push('css')
    <style>
        .producto-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cantidad-input {
            width: 70px;
            text-align: center;
        }
        .subtotal {
            font-weight: bold;
            color: #059669;
        }
        .stock-badge {
            font-size: 0.75rem;
        }
        .empty-cart {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-cart i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Mi Carrito de Compras</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Carrito</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($carritoItems->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-shopping-cart me-1"></i>
                        Productos en el Carrito ({{ $carritoItems->count() }})
                    </div>
                    <form action="{{ route('carrito.vaciar') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Estás seguro de vaciar el carrito?')">
                            <i class="fas fa-trash me-1"></i>
                            Vaciar Carrito
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carritoItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->producto->imagen)
                                            <img src="{{ asset('storage/' . $item->producto->imagen) }}" 
                                                 alt="{{ $item->producto->nombre }}" 
                                                 class="producto-img me-3">
                                        @else
                                            <div class="producto-img bg-light d-flex align-items-center justify-content-center me-3">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $item->producto->nombre }}</h6>
                                            <small class="text-muted">
                                                Categoría: {{ $item->producto->categoria->nombre }}<br>
                                                Vendedor: {{ $item->producto->vendedor->name }}
                                            </small>
                                            @if($item->producto->stock < 10)
                                                <div>
                                                    <span class="badge bg-warning stock-badge">
                                                        Solo {{ $item->producto->stock }} disponibles
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    ${{ number_format($item->producto->precio, 2) }}
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('carrito.decrementar', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm" 
                                                    {{ $item->cantidad <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </form>
                                        
                                        <span class="mx-2 fw-bold">{{ $item->cantidad }}</span>
                                        
                                        <form action="{{ route('carrito.incrementar', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm"
                                                    {{ $item->cantidad >= $item->producto->stock ? 'disabled' : '' }}>
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Stock: {{ $item->producto->stock }}
                                    </small>
                                </td>
                                <td class="align-middle subtotal">
                                    ${{ number_format($item->subtotal, 2) }}
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('carrito.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="subtotal">${{ number_format($total, 2) }}</td>
                                <td>
                                    <a href="{{ route('checkout') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-credit-card me-1"></i>
                                        Proceder al Pago
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-truck me-1"></i>
                        Información de Envío
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Dirección:</strong> {{ Auth::user()->direccion ?: 'No especificada' }}</p>
                        <p class="mb-2"><strong>Teléfono:</strong> {{ Auth::user()->telefono ?: 'No especificado' }}</p>
                        <a href="{{ route('perfil.edit') }}" class="btn btn-outline-primary btn-sm">
                            Actualizar información
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-1"></i>
                        Resumen del Pedido
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Productos:</strong> {{ $carritoItems->count() }}</p>
                        <p class="mb-2"><strong>Items totales:</strong> {{ $carritoItems->sum('cantidad') }}</p>
                        <p class="mb-2"><strong>Subtotal:</strong> ${{ number_format($total, 2) }}</p>
                        <p class="mb-2"><strong>Envío:</strong> $0.00</p>
                        <hr>
                        <p class="mb-0 fw-bold"><strong>Total:</strong> ${{ number_format($total, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Tu carrito está vacío</h3>
                    <p class="text-muted">Agrega algunos productos para comenzar a comprar</p>
                    <a href="{{ route('productos.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-1"></i>
                        Ver Productos
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection