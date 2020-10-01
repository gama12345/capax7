<head>
    <title>Capax7 - Estadísticas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Admin/Statistics.css') }}">
</head>
@extends('Admin.NavBar')
@section('content')
    <div id="statistics" class="statistics">
        <div class="title"><h1>ESTADÍSTICAS</h1></div>
        <div class="statistics__container">
            <div class="statistics__bestClients">
                <p id="pTitle">Mayores ingresos en donativos</p>
                <div class="statistics__clients">
                    <p>Histórico</p>
                    <input type="text" value=" {{ $topDonationsClients[0]->razon_social }}" disabled/>
                    <p>Año</p>
                    <input type="text" value="{{ $topAnualDonationsClients[0]->razon_social }}" disabled/>   
                    <p>Mes</p>
                    <input type="text" value="{{ $topMonthDonationsClients[0]->razon_social }}" disabled/>                  
                </div>
            </div>

            <div id="parentGraph" class="statistics__graph">
                <div class="statisticsDonations__typeGraph">
                    <label for="type" >Tipo:</label>
                    <div class="select">
                        <select id="type" onchange="makeGraph(this.value)">
                            <option>Histórico</option>
                            <option>Este año</option>
                            <option>Este mes</option>
                        </select>
                    </div>
                </div>
                <canvas id="graph"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        window.onload = function() {
            document.getElementById('statistics').className += " slideEffect";
        }
        var topDonationsClients = @json($topDonationsClients);
        var topAnualDonationsClients = @json($topAnualDonationsClients);
        var topMonthDonationsClients = @json($topMonthDonationsClients);
        makeGraph('Histórico')
        
        function makeGraph(type){
            var montos = []; var clientes = []; var fullNames = []; array = [];
            if(type == "Histórico"){
                array = topDonationsClients;
            }else if(type == "Este año"){
                array = topAnualDonationsClients;
            }else{
                array = topMonthDonationsClients;
            }
            array.forEach(client => {
                    nombre = client.razon_social;
                    if(client.razon_social.length > 15){
                        nombre = client.razon_social.substring(0,15)+"...";
                    }
                    montos.push(client.total);
                    clientes.push(nombre);
                    fullNames.push(client.razon_social);
                });
            clientes.reverse(); montos.reverse(); fullNames.reverse();

            //Remove old graph, create new one 
            graphic = document.getElementById('graph');
            graphic.parentNode.removeChild(graphic);
            parent = document.getElementById('parentGraph');
            newGraphic = document.createElement('canvas');
            newGraphic.setAttribute('id','graph');
            parent.appendChild(newGraphic);

            var ctx = document.getElementById('graph').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: clientes,
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
                        text: 'Clientes con mayores ingresos'
                    },
                    tooltips: {
                        callbacks: {
                            title: function(tooltipItem, data) {
                                return fullNames[tooltipItem[0].index];
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
        }
    </script>

@endsection