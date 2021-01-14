<head>
    <title>Capax7 - Registrar donación</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/registerExpense.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="registerExpense" class="registerExpense">
        <div class="title"><h1>REGISTRAR GASTO</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        
        <div class="registerExpense__sections">
            <fieldset class="registerExpense__donorInfoSection">
                    <legend>Nuevo registro</legend>
                    <form method="post" action="{{ route('registerExpense') }}" autocomplete="off" onKeyPress="return (event.keyCode !== 13)">
                        @csrf
                        <input id="id" name="id" hidden value="{{ old('id') }}"/>

                        <div class="registerExpense__inputData">
                                <input id="autoComplete" tabindex="1" name="concepto" type="text" title="Concepto del gasto" value="{{ old('concepto') }}"/>
                                <label title="Concepto del gasto">Concepto *</label>
                        </div>

                        <div class="registerExpense__inputData">
                            <input name="fecha" type="text" value="{{ $date }}" disabled/>
                            <label>Fecha </label>
                        </div>

                        <div class="registerExpense__inputData">
                                <input name="cantidad" type="text" value="{{ old('cantidad') }}" placeholder="0.00"/>
                                <label>Monto *</label>
                        </div>


                        <div class="registerExpense__dataMsg">
                            * Información requerida
                        </div>

                        <button class="registerExpense__donorInfoBtn">
                            <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                            </svg>
                            <span>Registrar gasto</span>
                        </button>
                        <button type="button" class="registerExpense__donorBackBtn" onclick="location.href = '{{ route('showDataMenu') }}'">
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
            document.getElementById('registerExpense').className += " slideEffect";
        }

    </script>

@endsection