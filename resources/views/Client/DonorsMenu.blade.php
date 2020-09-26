<head>
    <title>Capax7 - Menú donantes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/DonorsMenu.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="donorsMenu" class="donorsMenu">
        <div class="title"><h1>DONANTES</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        
        <div class="donorsMenu__menu">
            <div class="donorsMenu__rowMenu">
                <button class="donorsMenu__btn btnRegisterDonor" title="Nuevo donante" onclick="location.href = '{{ route('showRegisterDonor') }}'"></button>
                <button class="donorsMenu__btn btnDonors" title="Ver donantes" onclick="location.href = '{{ route('showDonors') }}'"></button>
            </div>
            <div class="donorsMenu__rowMenu">
                <button class="donorsMenu__btn btnRegisterDonation" title="Nueva donación" onclick="location.href = '{{ route('showRegisterDonation') }} '"></button>
                <button class="donorsMenu__btn btnDonations" title="Ver donaciones" onclick="location.href = '{{ route('showDonations') }}'"></button>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            document.getElementById('donorsMenu').className += " slideEffect";
        }
    </script>

@endsection