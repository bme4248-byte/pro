@extends('template')

@section('title', 'Editar Pedido')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Editar Pedido</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Editar Pedido: {{ $pedido->numero_pedido }}
        </div>
        <div class="card-body">
            <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="comprador_id" class="form-label">Comprador *</label>
                            <select class="form-control @error('comprador_id') is-invalid @enderror" 
                                    id="comprador_id" name="comprador_id" required>
                                <option value="">Seleccione un comprador</option>
                                @foreach($compradores as $comprador)
                                    <option value="{{ $comprador->id }}" {{ old('comprador_id', $pedido->comprador_id) == $comprador->id ? 'selected' : '' }}>
                                        {{ $comprador->name }} ({{ $comprador->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('comprador_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago *</label>
                            <select class="form-control @error('metodo_pago') is-invalid @enderror" 
                                    id="metodo_pago" name="metodo_pago" required>
                                <option value="">Seleccione método de pago</option>
                                <option value="tarjeta" {{ old('metodo_pago', $pedido->metodo_pago) == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                <option value="transferencia" {{ old('metodo_pago', $pedido->metodo_pago) == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                <option value="qr" {{ old('metodo_pago', $pedido->metodo_pago) == 'qr' ? 'selected' : '' }}>QR</option>
                                <option value="efectivo" {{ old('metodo_pago', $pedido->metodo_pago) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            </select>
                            @error('metodo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado del Pedido *</label>
                            <select class="form-control @error('estado') is-invalid @enderror" 
                                    id="estado" name="estado" required>
                                <option value="pendiente" {{ old('estado', $pedido->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmado" {{ old('estado', $pedido->estado) == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="enviado" {{ old('estado', $pedido->estado) == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                <option value="entregado" {{ old('estado', $pedido->estado) == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelado" {{ old('estado', $pedido->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="estado_pago" class="form-label">Estado del Pago *</label>
                            <select class="form-control @error('estado_pago') is-invalid @enderror" 
                                    id="estado_pago" name="estado_pago" required>
                                <option value="pendiente" {{ old('estado_pago', $pedido->estado_pago) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="pagado" {{ old('estado_pago', $pedido->estado_pago) == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                <option value="fallido" {{ old('estado_pago', $pedido->estado_pago) == 'fallido' ? 'selected' : '' }}>Fallido</option>
                            </select>
                            @error('estado_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="direccion_entrega" class="form-label">Dirección de Entrega *</label>
                            <textarea class="form-control @error('direccion_entrega') is-invalid @enderror" 
                                      id="direccion_entrega" name="direccion_entrega" rows="4" required>{{ old('direccion_entrega', $pedido->direccion_entrega) }}</textarea>
                            @error('direccion_entrega')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <small>
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Advertencia:</strong> Al cancelar el pedido, el stock de los productos será restaurado automáticamente.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Actualizar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection