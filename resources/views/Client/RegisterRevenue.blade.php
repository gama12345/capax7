<head>
    <title>Capax7 - Registrar donación</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/registerRevenue.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="registerRevenue" class="registerRevenue">
        <div class="title"><h1>REGISTRAR INGRESO</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        
        <div class="registerRevenue__sections">
            <fieldset class="registerRevenue__donorInfoSection">
                    <legend>Nuevo registro</legend>
                    <form method="post" action="{{ route('registerRevenue') }}" autocomplete="off" onKeyPress="return (event.keyCode !== 13)">
                        @csrf
                        <input id="id" name="id" hidden value="{{ old('id') }}"/>

                        <div class="registerRevenue__inputData">
                                <input id="autoComplete" tabindex="1" name="concepto" type="text" title="Concepto del ingreso" value="{{ old('concepto') }}"/>
                                <label title="Concepto del ingreso">Concepto *</label>
                        </div>

                        <div class="registerRevenue__inputData">
                            <input name="fecha" type="text" value="{{ $date }}" disabled/>
                            <label>Fecha </label>
                        </div>

                        <div class="registerRevenue__inputData">
                                <input name="cantidad" type="text" value="{{ old('cantidad') }}" placeholder="0.00"/>
                                <label>Monto *</label>
                        </div>


                        <div class="registerRevenue__dataMsg">
                            * Información requerida
                        </div>

                        <button class="registerRevenue__donorInfoBtn">
                            <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                            </svg>
                            <span>Registrar ingreso</span>
                        </button>
                        <button type="button" class="registerRevenue__donorBackBtn" onclick="location.href = '{{ route('showDataMenu') }}'">
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
            document.getElementById('registerRevenue').className += " slideEffect";
        }

    </script>

@endsection