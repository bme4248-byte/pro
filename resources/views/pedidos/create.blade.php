@extends('template')

@section('title', 'Crear Pedido')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Crear Nuevo Pedido</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Formulario de Nuevo Pedido
        </div>
        <div class="card-body">
            <form action="{{ route('pedidos.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="comprador_id" class="form-label">Comprador *</label>
                            <select class="form-control @error('comprador_id') is-invalid @enderror" 
                                    id="comprador_id" name="comprador_id" required>
                                <option value="">Seleccione un comprador</option>
                                @foreach($compradores as $comprador)
                                    <option value="{{ $comprador->id }}" {{ old('comprador_id') == $comprador->id ? 'selected' : '' }}>
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
                                <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                <option value="qr" {{ old('metodo_pago') == 'qr' ? 'selected' : '' }}>QR</option>
                                <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            </select>
                            @error('metodo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="estado_pago" class="form-label">Estado del Pago *</label>
                            <select class="form-control @error('estado_pago') is-invalid @enderror" 
                                    id="estado_pago" name="estado_pago" required>
                                <option value="pendiente" {{ old('estado_pago') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="pagado" {{ old('estado_pago') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                <option value="fallido" {{ old('estado_pago') == 'fallido' ? 'selected' : '' }}>Fallido</option>
                            </select>
                            @error('estado_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="direccion_entrega" class="form-label">Dirección de Entrega *</label>
                            <textarea class="form-control @error('direccion_entrega') is-invalid @enderror" 
                                      id="direccion_entrega" name="direccion_entrega" rows="4" 
                                      placeholder="Ingrese la dirección completa de entrega..." required>{{ old('direccion_entrega') }}</textarea>
                            @error('direccion_entrega')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                El número de pedido se generará automáticamente después de guardar.
                                Puede agregar productos al pedido desde la sección de detalles una vez creado.
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
                        Crear Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection