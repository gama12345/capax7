<head>
    <title>Capax7 - Estadísticas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/statisticsDonors.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="statisticsDonors" class="statisticsDonors">
        <div class="title"><h1>ESTADÍSTICAS</h1></div>

        <div class="statisticsDonors__container">
            <div class="statisticsDonors__typeGraph">
                <label for="type" >Tipo:</label>
                <div class="select">
                    <select id="type" onchange="makeGraph(this.value)">
                        <option>Histórico</option>
                        <option>Este año</option>
                        <option>Este mes</option>
                    </select>
                </div>
            </div>

            <div id="parentGraph" class="statisticsDonors__donorsGraph">
                <canvas id="graph"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        window.onload = function() {
            document.getElementById('statisticsDonors').className += " slideEffect";
        }
        var historicDonors = @json($historicDonors);
        var anualDonors = @json($anualDonors);
        var monthDonors = @json($monthDonors);
        makeGraph('Histórico');
       /* arrayDonations.forEach(donation => {
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
        fechas.reverse(); montos.reverse(); donantes.reverse();*/

        function makeGraph(type){
            var array = []; var montos = []; var donantes = []; var text;
            if(type === "Histórico"){
                text = "Mejores donantes histórico";
                array = historicDonors;
            }else if(type === "Este año"){
                text = "Mejores donantes del año";
                array = anualDonors;
            }else{                
                text = "Mejores donantes del mes";
                array = monthDonors;
            }
            array.forEach(donor => {
                    nombre = donor.razon_social;
                    if(donor.razon_social.length > 10){
                        nombre = donor.razon_social.substring(0,10)+"...";
                    }
                    donantes.push(nombre);
                    montos.push(donor.total);                    
                });
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
                        text: text
                    },
                    tooltips: {
                        callbacks: {
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