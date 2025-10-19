@extends('template')

@section('title', 'Crear Pago')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Crear Nuevo Pago</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}">Pagos</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Formulario de Nuevo Pago
        </div>
        <div class="card-body">
            <form action="{{ route('pagos.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pedido_id" class="form-label">Pedido *</label>
                            <select class="form-control @error('pedido_id') is-invalid @enderror" 
                                    id="pedido_id" name="pedido_id" required>
                                <option value="">Seleccione un pedido</option>
                                @foreach($pedidos as $pedido)
                                    <option value="{{ $pedido->id }}" 
                                            data-total="{{ $pedido->total }}"
                                            {{ old('pedido_id') == $pedido->id ? 'selected' : '' }}>
                                        Pedido #{{ $pedido->numero_pedido }} - {{ $pedido->comprador->name }} - Total: ${{ number_format($pedido->total, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pedido_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="monto" class="form-label">Monto *</label>
                            <input type="number" step="0.01" class="form-control @error('monto') is-invalid @enderror" 
                                   id="monto" name="monto" value="{{ old('monto') }}" min="0" required>
                            @error('monto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago *</label>
                            <input type="text" class="form-control @error('metodo_pago') is-invalid @enderror" 
                                   id="metodo_pago" name="metodo_pago" value="{{ old('metodo_pago') }}" 
                                   placeholder="Ej: Tarjeta de Crédito, Transferencia, etc." required>
                            @error('metodo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-control @error('estado') is-invalid @enderror" 
                                    id="estado" name="estado" required>
                                <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="fallido" {{ old('estado') == 'fallido' ? 'selected' : '' }}>Fallido</option>
                                <option value="reembolsado" {{ old('estado') == 'reembolsado' ? 'selected' : '' }}>Reembolsado</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="codigo_transaccion" class="form-label">Código de Transacción</label>
                            <input type="text" class="form-control @error('codigo_transaccion') is-invalid @enderror" 
                                   id="codigo_transaccion" name="codigo_transaccion" value="{{ old('codigo_transaccion') }}"
                                   placeholder="Dejar vacío para generar automáticamente">
                            @error('codigo_transaccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                            <input type="datetime-local" class="form-control @error('fecha_pago') is-invalid @enderror" 
                                   id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago') }}">
                            @error('fecha_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Dejar vacío para usar fecha y hora actual</div>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Nota:</strong> Al marcar un pago como "Completado", se actualizará automáticamente el estado del pedido a "Pagado".
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('pagos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Crear Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pedidoSelect = document.getElementById('pedido_id');
        const montoInput = document.getElementById('monto');

        pedidoSelect.addEventListener('change', function() {
            const selectedOption = pedidoSelect.options[pedidoSelect.selectedIndex];
            if (selectedOption.value) {
                const total = selectedOption.getAttribute('data-total');
                montoInput.value = total;
            } else {
                montoInput.value = '';
            }
        });
    });
</script>
@endsection