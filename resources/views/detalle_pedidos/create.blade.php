@extends('template')

@section('title', 'Crear Detalle de Pedido')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Crear Nuevo Detalle de Pedido</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('detalle-pedidos.index') }}">Detalle de Pedidos</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Formulario de Nuevo Detalle de Pedido
        </div>
        <div class="card-body">
            <form action="{{ route('detalle-pedidos.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pedido_id" class="form-label">Pedido *</label>
                            <select class="form-control @error('pedido_id') is-invalid @enderror" 
                                    id="pedido_id" name="pedido_id" required>
                                <option value="">Seleccione un pedido</option>
                                @foreach($pedidos as $pedido)
                                    <option value="{{ $pedido->id }}" {{ old('pedido_id') == $pedido->id ? 'selected' : '' }}>
                                        Pedido #{{ $pedido->numero_pedido }} - {{ $pedido->comprador->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pedido_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="producto_id" class="form-label">Producto *</label>
                            <select class="form-control @error('producto_id') is-invalid @enderror" 
                                    id="producto_id" name="producto_id" required>
                                <option value="">Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" 
                                            data-stock="{{ $producto->stock }}"
                                            data-precio="{{ $producto->precio }}"
                                            {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                        {{ $producto->nombre }} - Stock: {{ $producto->stock }} - ${{ number_format($producto->precio, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('producto_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vendedor_id" class="form-label">Vendedor *</label>
                            <select class="form-control @error('vendedor_id') is-invalid @enderror" 
                                    id="vendedor_id" name="vendedor_id" required>
                                <option value="">Seleccione un vendedor</option>
                                @foreach($vendedores as $vendedor)
                                    <option value="{{ $vendedor->id }}" {{ old('vendedor_id') == $vendedor->id ? 'selected' : '' }}>
                                        {{ $vendedor->name }} ({{ $vendedor->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('vendedor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cantidad" class="form-label">Cantidad *</label>
                                    <input type="number" class="form-control @error('cantidad') is-invalid @enderror" 
                                           id="cantidad" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" required>
                                    @error('cantidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text" id="stock-info">Stock disponible: -</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio_unitario" class="form-label">Precio Unitario *</label>
                                    <input type="number" step="0.01" class="form-control @error('precio_unitario') is-invalid @enderror" 
                                           id="precio_unitario" name="precio_unitario" value="{{ old('precio_unitario') }}" min="0" required>
                                    @error('precio_unitario')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subtotal Calculado</label>
                            <div class="form-control" id="subtotal-calculado" style="background-color: #f8f9fa;">
                                $0.00
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('detalle-pedidos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Guardar Detalle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productoSelect = document.getElementById('producto_id');
        const cantidadInput = document.getElementById('cantidad');
        const precioInput = document.getElementById('precio_unitario');
        const stockInfo = document.getElementById('stock-info');
        const subtotalCalculado = document.getElementById('subtotal-calculado');

        function actualizarStockYPrecio() {
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            if (selectedOption.value) {
                const stock = selectedOption.getAttribute('data-stock');
                const precio = selectedOption.getAttribute('data-precio');
                
                stockInfo.textContent = `Stock disponible: ${stock}`;
                precioInput.value = precio;
                calcularSubtotal();
            } else {
                stockInfo.textContent = 'Stock disponible: -';
                precioInput.value = '';
                calcularSubtotal();
            }
        }

        function calcularSubtotal() {
            const cantidad = parseInt(cantidadInput.value) || 0;
            const precio = parseFloat(precioInput.value) || 0;
            const subtotal = cantidad * precio;
            
            subtotalCalculado.textContent = `$${subtotal.toFixed(2)}`;
        }

        productoSelect.addEventListener('change', actualizarStockYPrecio);
        cantidadInput.addEventListener('input', calcularSubtotal);
        precioInput.addEventListener('input', calcularSubtotal);

        // Inicializar
        actualizarStockYPrecio();
    });
</script>
@endsection