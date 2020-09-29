<head>
    <title>Capax7 - Estadísticas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/Statistics.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="statistics" class="statistics">
        <div class="title"><h1>ESTADÍSTICAS</h1></div>
        <div class="statistics__container">
            <div class="statistics__averageData">
                <div class="statistics__bestDonor">
                    <p>Donante del año</p>
                    <input id="bestDonor" type="text" value="{{ $bestDonor }}" disabled/>
                    <p>Promedio mensual</p>
                    <input id="avgDonor" type="text" value="${{ number_format($monthAvg, 2) }} MXN" disabled/>   
                    <a href="{{ route('showDetailedDonors') }}">Ver más...</a>                 
                </div>
                <div class="statistics__bestMonth">
                    <p>Recaudación anual</p>
                    <input id="anual" type="text" value="${{ number_format($anualIncome, 2) }} MXN" disabled/>
                    <p>Promedio mensual</p>
                    <input id="avgMonth" type="text" value="${{ number_format($anualMonthAvg, 2) }} MXN" disabled/>   
                    <a href="#">Ver más...</a>                    
                </div>
            </div>

            <div class="statistics__lastDonationsGraph">
                <canvas id="graph"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        window.onload = function() {
            document.getElementById('statistics').className += " slideEffect";
        }
        var arrayDonations = @json($last5Donations);
        var arrayDonors = @json($donors);
        var fechas = []; var montos = []; var donantes = [];
        arrayDonations.forEach(donation => {
            for(index=0; index<arrayDonors.length; index++){
                if(arrayDonors[index].id === donation.donante){
                    nombre = arrayDonors[index].razon_social;
                    if(arrayDonors[index].razon_social.length > 10){
                        nombre = arrayDonors[index].razon_social.substring(0,10)+"...";
                    }
                    donantes.push(nombre);
                    break;
                }
            };
            fechas.push(donation.fecha);
            montos.push(donation.cantidad);
        });
        fechas.reverse(); montos.reverse(); donantes.reverse();

        var ctx = document.getElementById('graph').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: donantes,
                datasets: [{
                    label: "Donaciones",
                    backgroundColor: ['rgb(49, 179, 218)'],
                    borderColor: 'rgb(49, 179, 218)',
                    borderWidth: 2,
                    data: montos,
                    fill: false,
                    lineTension: 0,
                }]
            },
            options: {
                title: {
                    fontSize: 15,
                    display: true,
                    text: 'Últimas donaciones'
                },
                tooltips: {
                    callbacks: {
                        title: function(tooltipItem, data) {
                            return "Fecha: "+fechas[tooltipItem[0].index];
                        },
                        label: function(tooltipItem, data) {
                            return "Monto: "+tooltipItem.yLabel;
                        },
                        labelColor: function(tooltipItem, chart) {
                            return {
                                backgroundColor: 'rgb(49, 179, 218)'
                            };
                        },
                    }
                }
            }
        });
    </script>

@endsection