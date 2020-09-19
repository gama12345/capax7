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
                <a href="{{ route('home','admin') }}"><img class="menuElement" src="/Icons/home-page.png" alt="No se pudo cargar el icono"></img></a>
                <a><img class="menuElement" src="/Icons/register-client.png" alt="No se pudo cargar el icono"></img></a>
                <a><img class="menuElement" src="/Icons/stadistics.png" alt="No se pudo cargar el icono"></img></a>
                <a href="{{ route('logout', 'admin') }}"><img class="menuElement" src="/Icons/logout.png" alt="No se pudo cargar el icono" placeholder="Salir"></img></a>
            </nav>
        </div>

        @yield('content')
    </body>
</html>