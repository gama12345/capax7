<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capax7 - Reestablecer contraseña</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/ResetPassword/SendRequest.css') }}">
</head>
<body>
    <div class="sendRequest">
        <h2>¿Has olvidado tu contraseña?</h2>
        <p>Ingresa tu correo electrónico registrado y te enviaremos un enlace junto con instrucciones para
            poder reestablecer tu contraseña. Si has olvidado tu contraseña y correo electrónico registrado, 
            por favor ponte en contacto con nosotros en nuestra <a href="https://www.capax7consultores.com/">página web</a> o en nuestras <a href="https://www.facebook.com/capax7consultores/">redes sociales</a>.
        </p>
        
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif

        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif

        @if(Session::has('failed'))
            <div class="failed">{{  Session::get('failed')  }}</div>
        @endif

        <label>Email</label>
        <form action="{{ route('validateRequestPassword') }}" method="post">
            @csrf
            <input class="data" name="email" type="text" />
            <br>
            <button class="btn">Enviar instrucciones</button>
            <button type="button" class="btn" onclick='location.href="{{ route('main') }}"'>Volver</button>
        </form>
    </div>
</body>
</html>