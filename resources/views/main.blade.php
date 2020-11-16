<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Capax7</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/main.css') }}">
    </head>
    <body>
        <div class="login">
            <div class="login__bannerSection">
                <div class="login__bannerImage">
                    <div class="login__bannerSocialNetworks">
                        <ul>
                        @if($web)
                                <li class="li-web">
                                    <a href="{{ $web }}">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span><img src="{{ asset('/Icons/icons-website.png') }}" alt="" title="Ir a sitio web"/></span>
                                    </a>
                                </li>
                            @endif
                            @if($facebook)
                                <li class="li-facebook">
                                    <a href="https://www.facebook.com/{{ $facebook }}">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span><img src="{{ asset('/Icons/fb.png') }}" alt="" title="Ir a perfil de Facebook"/></span>
                                    </a>
                                </li>
                            @endif
                            
                            @if($instagram)
                                <li class="li-instagram">
                                    <a href="https://www.instagram.com/{{ $instagram }}">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span><img class="icon" src="{{ asset('/Icons/instagram.png') }}" alt="" title="Ir a cuenta de Instagram"/></span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="login__loginSection">
                <a href="{{ $web }}"><img class="login__loginLogo" src="/storage/admin/{{ $logo }}" alt="No se pudo cargar la imagen de inicio" title="Visitanos en nuestra página web"/></a>
                @if($errors->has('noLogin'))
                    <div class="noLogin">{{  $errors->first('noLogin')  }}</div>
                @endif
                <form method="post" action="{{ route('login') }}">
                @csrf
                    <div class="login__inputData">    
                        <input name="email" type="text" title="" required oninvalid="if (this.value == ''){this.setCustomValidity('Ingrese su email antes de continuar')} if (this.value != ''){this.setCustomValidity('El email ingresado es incorrecto')}" oninput="setCustomValidity('')"/>
                        <label>Email</label>
                        @if($errors->has('email'))
                            <div class="error">{{  $errors->first('email')  }}</div>
                        @endif
                    </div>
                    <div class="login__inputData">    
                        <input name="password" type="password" title="" required oninvalid="if (this.value == ''){this.setCustomValidity('Ingrese su nueva contraseña')} if (this.value != ''){this.setCustomValidity('La contraseña ingresada es incorrecta')}" oninput="setCustomValidity('')"/>
                        <label>Contraseña</label>
                        @if($errors->has('contraseña'))
                            <div class="error">{{  $errors->first('contraseña')  }}</div>
                        @endif
                    </div>
                    <button>Acceder</button>
                </form>
                <a href="{{ route('showRequestPassword') }}">Olvidaste tu contraseña?</a>
            </div>
        </div>
    </body>
</html>
