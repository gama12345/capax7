<head>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Admin/Home.css') }}">
</head>
@extends('Admin.NavBar')
@section('content')
    <div id="home"  class="home">
        <div class="title"><h1>PANEL DE CONTROL</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        <div class="home__sections">
            <fieldset class="home__adminInfoSection">
                <legend>Administrativos</legend>
                <form method="post" action="{{ route('updateAdministrativeInformationAdmin') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="home__inputData">
                            <input name="razon_social" type="text" value="{{ $datos->razon_social }}"/>
                            <label>Razón social</label>
                    </div>

                    <div class="home__inputData">
                            <input name="rfc" type="text" value="{{ $datos->rfc }}"/>
                            <label>RFC</label>
                    </div>

                    <div class="home__inputData">
                            <input name="presidente" type="text" value="{{ $datos->presidente }}"/>
                            <label>Presidente</label>
                    </div>

                    <div class="home__inputData">
                            <input name="director_ejecutivo" type="text" value="{{ $datos->director_ejecutivo }}"/>
                            <label>Director Ejecutivo</label>
                    </div>

                    <div class="home__inputData">
                        <p>Cambiar logo</p>
                        <input type="file" name="logo" accept="image/png, image/jpeg, image/jpg" class="home__docBtn" onchange="cambiarLogo(this)"/>
                        <img id="imgLogo" src="/storage/admin/{{ $datos->logo }}" alt="No se ha podido cargar la imagen"/>
                    </div>

                    <button class="home__adminInfoBtn">
                        <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                            <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                            <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                        </svg>
                        <span>Guardar cambios</span>
                    </button>
                </form>
            </fieldset>

            <fieldset class="home__gralInfoSection">
                <legend>Generales</legend>
                <form method="post" action="{{ route('updateGeneralInformationAdmin') }}" autocomplete="off">
                    @csrf
                    <div class="home__inputData">
                            <input name="email" type="text" value="{{ $datos->email }}"/>
                            <label>Email</label>
                    </div>

                    <div class="home__inputData">
                            <input name="contraseña" type="password" value="******"/>
                            <label>Contraseña</label>
                    </div>

                    <div class="home__inputData" >
                            <input name="telefono" type="text" value="{{ $datos->telefono }}"/>
                            <label>Teléfono</label>
                    </div>

                    <div class="home__inputData">
                            <input name="direccion" type="text" value="{{ $datos->direccion }}"/>
                            <label>Dirección</label>
                    </div>

                    <div class="home__inputData">
                        <input name="pagina_web" type="text" value="{{ $datos->pagina_web }}"/>                            
                        <a href="{{ $datos->pagina_web }}">Página web</a>
                    </div>

                    <div class="home__socialNetworkItem">
                        <input name="facebook" type="text" value="{{ $datos->facebook }}"/>
                        <a href='https://www.facebook.com/{{$datos->facebook}}'>Facebook</a>
                    </div>
                    <div class="home__socialNetworkItem">
                        <input name="twitter" type="text" value="{{ $datos->twitter }}"/>
                        <a href='https://www.twitter.com/{{$datos->twitter}}'>Twitter</a>
                    </div>
                    <div class="home__socialNetworkItem">
                        <input name="instagram" type="text" value="{{ $datos->instagram }}"/>
                        <a href='https://www.instagram.com/{{$datos->instagram}}'>Instagram</a>
                    </div>

                    <button class="home__adminInfoBtn">
                        <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                            <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                            <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                        </svg>
                        <span>Guardar cambios</span>
                    </button>
                </form>
            </fieldset>
    </div>
    <script>
        window.onload = function() {
            document.getElementById('home').className += " slideEffect";
        }
        function cambiarLogo(input){
            var reader = new FileReader();
    
            reader.onload = function(e) {
                document.getElementById('imgLogo').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]); 
        }
    </script>
@endsection