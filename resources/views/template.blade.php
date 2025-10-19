<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Carcacha" />
        <meta name="author" content="" />
        <title>Carcacha - @yield('title')</title>
        {{-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> --}}
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        
        @stack('css')
    
    </head>
    
    <body class="sb-nav-fixed">
       
       <x-navigation-header/>

        <div id="layoutSidenav">
         
    <x-navigation-menu/>      

            <div id="layoutSidenav_content">
                <main>
                   @yield('content')
                </main>

              
    <x-footer />

            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        
        <!-- Script para el contador del carrito -->
        <script>
        // Actualizar contador del carrito cada 30 segundos
        function actualizarContadorCarrito() {
            fetch('{{ route("carrito.contador") }}')
                .then(response => response.json())
                .then(data => {
                    const counter = document.getElementById('carrito-counter');
                    if (counter) {
                        counter.textContent = data.contador;
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Actualizar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            actualizarContadorCarrito();
            
            // Actualizar cada 30 segundos
            setInterval(actualizarContadorCarrito, 30000);
        });

        // Actualizar contador después de agregar al carrito
        document.addEventListener('DOMContentLoaded', function() {
            // Escuchar envíos de formularios de carrito
            document.addEventListener('submit', function(e) {
                if (e.target && e.target.action && e.target.action.includes('/carrito')) {
                    // Esperar un poco para que se procese la solicitud
                    setTimeout(actualizarContadorCarrito, 1000);
                }
            });
        });
        </script>

        @stack('js')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script> --}}
        <script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script>
        <script src="{{ asset('assets/demo/chart-bar-demo.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
    </body>
</html>