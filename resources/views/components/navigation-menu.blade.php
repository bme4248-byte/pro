<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="{{ route('panel') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Panel
                </a>
                
                <div class="sb-sidenav-menu-heading">Módulos</div>
                
                <a class="nav-link" href="{{ route('categorias.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                    Categorías
                </a>
                
                <a class="nav-link" href="{{ route('productos.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                    Productos
                </a>
                
                <a class="nav-link" href="{{ route('carrito.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                    Mi Carrito
                    <span class="badge bg-danger ms-2" id="carrito-counter">
                        {{ \App\Models\Carrito::where('usuario_id', Auth::id() ?? 1)->count() }}
                    </span>
                </a>
                
                <a class="nav-link" href="{{ route('pedidos.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Pedidos
                </a>
                
                <a class="nav-link" href="{{ route('detalle-pedidos.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list-alt"></i></div>
                    Detalle Pedidos
                </a>
                
                <a class="nav-link" href="{{ route('pagos.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                    Pagos
                </a>
            </div>
        </div>

        <div class="sb-sidenav-footer">
            <div class="small">Bienvenido:</div>
            {{ Auth::user()->name ?? 'Usuario' }}
        </div>
    </nav>
</div>