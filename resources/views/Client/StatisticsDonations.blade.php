<head>
    <title>Capax7 - Estadísticas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/statisticsDonations.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="statisticsDonations" class="statisticsDonations">
        <div class="title"><h1>ESTADÍSTICAS</h1></div>

        <div class="statisticsDonations__container">
            <div class="statisticsDonations__typeGraph">
                <label for="type" >Tipo:</label>
                <div class="select">
                    <select id="type" onchange="makeGraph(this.value)">
                        <option>Últimos años</option>
                        <option>Últimos meses</option>
                    </select>
                </div>
            </div>

            <div id="parentGraph" class="statisticsDonations__donorsGraph">
                <canvas id="graph"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        window.onload = function() {
            document.getElementById('statisticsDonations').className += " slideEffect";
        }
        var thisYear = @json($thisYearDonations);
        var lastYear = @json($lastYearDonations);
        var lastLastYear = @json($lastLastYearDonations);
        var thisMonth = @json($thisMonthDonations);
        var lastMonth = @json($lastMonthDonations);
        var lastLastMonth = @json($lastLastMonthDonations);
        
        makeGraph('Últimos años');

        function makeGraph(type){
            var xAxis = []; var montos = []; var text;

            if(type === "Últimos años"){
                text = "Últimos 3 años";
                xAxis = @json($years);
                montos.push(lastLastYear);
                montos.push(lastYear);
                montos.push(thisYear);
            }else if(type === "Últimos meses"){
                text = "Últimos 3 meses";               
                montos.push(lastLastMonth);
                montos.push(lastMonth);
                montos.push(thisMonth);
                xAxis = setLanguageMonths(@json($months)); 
            }else{                
                text = "Mejores donantes del mes";
                
            }
            //Remove old graph, create new one 
            graphic = document.getElementById('graph');
            graphic.parentNode.removeChild(graphic);
            parent = document.getElementById('parentGraph');
            newGraphic = document.createElement('canvas');
            newGraphic.setAttribute('id','graph');
            parent.appendChild(newGraphic);
            var ctx = document.getElementById('graph').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: xAxis,
                    datasets: [{
                        label: "Donaciones",
                        backgroundColor: ['rgb(49, 179, 218)','rgb(19, 48, 212)','rgb(19, 212, 138)'],
                        borderColor: ['rgb(49, 179, 218)','rgb(19, 48, 212)','rgb(19, 212, 138)'],
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

        function setLanguageMonths(monthsName){
            var nombres = [];
            monthsName.forEach(month => {
                switch(month){
                    case 1 || "01":
                        nombres.push("Enero");
                    break;
                    case 2 || "02":
                        nombres.push("Febrero");
                    break;
                    case 3 || "03":
                        nombres.push("Marzo");
                    break;
                    case 4 || "04":
                        nombres.push("Abril");
                    break;
                    case 5 || "05":
                        nombres.push("Mayo");
                    break;
                    case 6 || "06":
                        nombres.push("Junio");
                    break;
                    case 7 || "07":
                        nombres.push("Julio");
                    break;
                    case 8: case "08":
                        nombres.push("Agosto");
                    break;
                    case 9: case "09":
                        nombres.push("Septiembre");
                    break;
                    case 10: case "10":
                        nombres.push("Octubre");
                    break;
                    case 11: case "11":
                        nombres.push("Noviembre");
                    break;
                    case 12: case "12":
                        nombres.push("Diciembre");
                    break;
                }
            });
            return nombres;
        }
    </script>

@endsection