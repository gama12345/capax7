<head>
    <title>Capax7 - Menú datos</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/DataMenu.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="dataMenu" class="dataMenu">
        <div class="title"><h1>FINANZAS</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        
        <div class="dataMenu__menu">
            <div class="dataMenu__rowMenu">
                <button class="dataMenu__btn btnRegisterRevenue" title="Nuevo ingreso" onclick="location.href = '{{ route('showRegisterRevenue') }}'"></button>
                <button class="dataMenu__btn btnRevenues" title="Ver ingresos" onclick="location.href = '{{ route('showRevenues') }}'"></button>
            </div>
            <div class="dataMenu__rowMenu">
                <button class="dataMenu__btn btnRegisterExpense" title="Nuevo gasto" onclick="location.href = '{{ route('showRegisterExpense') }} '"></button>
                <button class="dataMenu__btn btnExpenses" title="Ver gastos" onclick="location.href='{{ route('showExpenses') }}'"></button>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            document.getElementById('dataMenu').className += " slideEffect";
        }
    </script>

@endsection