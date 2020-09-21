<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Capax7 - Administrador</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/Admin/NavBar.css') }}">
    </head>
    <body>
        <div class="navbar">
             <nav>
                <a href="{{ route('home','admin') }}" title="Inicio"><img class="menuElement" src="/Icons/home-page.png" alt="No se pudo cargar el icono"></img><p>INICIO</p></a>                
                <a href="{{ route('showRegisterClient') }}" title="Registrar nuevo"><img class="menuElement" src="/Icons/register-client.png" alt="No se pudo cargar el icono"></img><p>CLIENTE</p></a>
                <a title="Ver estadisticas"><img class="menuElement" src="/Icons/stadistics.png" alt="No se pudo cargar el icono"></img><p>DATOS</p></a>
                <a href="{{ route('logout', 'admin') }}" title="Cerrar sesión"><img class="menuElement" src="/Icons/logout.png" alt="No se pudo cargar el icono"></img><p>SALIR</p></a>
            </nav>
        </div>

        @yield('content')
    </body>
</html>