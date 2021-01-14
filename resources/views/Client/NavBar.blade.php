<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Capax7 - Usuario</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/NavBar.css') }}">
    </head>
    <body>
        <div class="navbar">
             <nav>
                <a href="{{ route('home','usuario') }}" title="Mis datos"><img class="menuElement" src="/Icons/home-page.png" alt="No se pudo cargar el icono"></img><p>MIS DATOS</p></a>                          
                <a href="{{ route('showDonorsMenu') }}" title="Donantes"><img class="menuElement" src="/Icons/donors.png" alt="No se pudo cargar el icono"></img><p>DONANTE</p></a>
                <a href="{{ route('showDataMenu') }}" title="Ver ingresos y gastos"><img class="menuElement" src="/Icons/stadistics.png" alt="No se pudo cargar el icono"></img><p>FINANZAS</p></a>
                <a href="{{ route('logout', 'admin') }}" title="Cerrar sesión"><img class="menuElement" src="/Icons/logout.png" alt="No se pudo cargar el icono"></img><p>SALIR</p></a>
                
            </nav>
        </div>

        @yield('content')
    </body>
</html>