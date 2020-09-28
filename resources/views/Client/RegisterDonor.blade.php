<head>
    <title>Capax7 - Registrar donante</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/RegisterDonor.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="registerDonor" class="registerDonor">
        <div class="title"><h1>REGISTRAR DONANTE</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        
        <div class="registerDonor__sections">
            <fieldset class="registerDonor__donorInfoSection">
                    <legend>Nuevo registro</legend>
                    <form method="post" action="{{ route('registerDonor') }}">
                        @csrf
                        <div class="registerDonor__inputData">
                                <input name="razon_social" type="text" value="{{ old('razon_social') }}"/>
                                <label>Razón social *</label>
                        </div>

                        <div class="registerDonor__inputData">
                            <select type="text" id="select_tipo_persona" name="tipo_persona" onchange="orgCheckType(this.value)">
                                <option value="Fisica">Fisica</option>
                                <option value="Moral">Moral</option>
                            </select>                        
                            <label>Persona</label>
                        </div>

                        <div class="registerDonor__inputData">
                                <input name="rfc" type="text" value="{{ old('rfc') }}"/>
                                <label>RFC *</label>
                        </div>

                        <div class="registerDonor__inputData">
                                <input name="nacionalidad" type="text" value="{{ old('nacionalidad') }}"/>
                                <label>Nacionalidad *</label>
                        </div>

                        <div class="registerDonor__inputData">
                                <input name="email" type="text" value="{{ old('email') }}"/>
                                <label>Email *</label>
                        </div>

                        <div class="registerDonor__inputData">
                                <input name="telefono" type="text" value="{{ old('telefono') }}"/>
                                <label>Teléfono *</label>
                        </div>

                        <div class="registerDonor__inputData">
                                <input name="celular" type="text" value="{{ old('celular') }}"/>
                                <label>Celular</label>
                        </div>

                        <div class="registerDonor__inputData">
                                <input name="domicilio" type="text" value="{{ old('domicilio') }}" placeholder="Calle ... #... Colonia ... C.P. ..."/>
                                <label title="Calle, número, colonia y código postal">Domicilio *</label>
                        </div>

                        <div class="registerDonor__dataMsg">
                            * Información requerida
                        </div>

                        <button class="registerDonor__donorInfoBtn">
                            <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                            </svg>
                            <span>Registrar donante</span>
                        </button>
                        <button type="button" class="registerDonor__donorBackBtn" onclick="location.href = '{{ route('showDonorsMenu') }}'">
                            <svg width="100px" height="25px" viewBox="0 0 100 25" class="border">
                                <polyline points="99,1 99,24 1,24 1,1 99,1" class="bg-line" />
                                <polyline points="99,1 99,24 1,24 1,1 99,1" class="hl-line" />
                            </svg>
                            <span>Regresar</span>
                        </button>
                </form>
            </fieldset>
        </div>
    </div>

    <script>
        window.onload = function() {
            document.getElementById('registerDonor').className += " slideEffect";
        }
    </script>

@endsection