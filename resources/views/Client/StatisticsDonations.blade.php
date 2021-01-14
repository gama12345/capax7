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
        
        <div id="statisticsDonations__fechas">
                <div class="statisticsDonations__typeGraph">
                    <label for="years" >Año:</label>
                    <div class="select">
                        <select id="years" onchange="setYear()" >
                            
                        </select>
                    </div>
                </div>
                <div class="statisticsDonations__typeGraph">
                    <label for="months" >Mes:</label>
                    <div class="select">
                        <select id="months" onchange="setMonth()">
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                </div>
                <form method="post" action="{{ route('showDetailedDonationsMonthYear') }}">
                @csrf
                    <input id="mes" name="month" hidden/>
                    <input id="año" name="year" hidden/>
                    <button class="btn" type="submit">Ver gráfico</button>
                </form>
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
        /*
        var thisYear = @(thisYearDonations);
        var lastYear = @(lastYearDonations);
        var lastLastYear = json(lastLastYearDonations);
        var thisMonth = json(thisMonthDonations);
        var lastMonth = json(lastMonthDonations);
        var lastLastMonth = json(lastLastMonthDonations);
        
        makeGraph('Últimos años');
        */

        var donaciones = @json($donaciones);
        var añoRegistro = @json($añoRegistro);
        var añoActual = @json($añoActual);
        var mesSeleccionado = @json($mesSeleccionado);
        var añoSeleccionado = @json($añoSeleccionado);
        while(añoActual >= añoRegistro){
            document.getElementById('years').innerHTML += "<option>"+añoActual+"</option>";
            añoActual--;
        }
        var montos = [];
        for(i=0; i<donaciones.length; i++){
            montos.push(donaciones[i].total);
        }
        if(mesSeleccionado != "" && añoSeleccionado != ""){
            document.getElementById("months").value = mesSeleccionado;
            document.getElementById("years").value = añoSeleccionado;
        }
        document.getElementById("mes").value = document.getElementById("months").value;
        document.getElementById("año").value = document.getElementById("years").value;
        makeGraph();

        function makeGraph(){
            var xAxis = [];  var text;
/*
            if(type === "Últimos años"){
                text = "Últimos 3 años";
                xAxis = json($years);
                montos.push(lastLastYear);
                montos.push(lastYear);
                montos.push(thisYear);
            }else if(type === "Últimos meses"){
                text = "Últimos 3 meses";               
                montos.push(lastLastMonth);
                montos.push(lastMonth);
                montos.push(thisMonth);
                xAxis = setLanguageMonths(json($months)); 
            }else{                
                text = "Mejores donantes del mes";
                
            }
            */
            xAxis.push(setLanguageMonths(mesSeleccionado)); 
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
                        text: "Donaciones de "+xAxis[0]+" "+añoSeleccionado
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

        function setMonth(){
            document.getElementById("mes").value = document.getElementById("months").value;
        }
        function setYear(){
            document.getElementById("año").value = document.getElementById("years").value;
        }
        function setLanguageMonths(month){
                switch(month){
                    case 1: case "01":
                        return "Enero";
                    break;
                    case 2: case "02":
                        return "Febrero";
                    break;
                    case 3: case "03":
                        return "Marzo";
                    break;
                    case 4: case "04":
                        return "Abril";
                    break;
                    case 5: case "05":
                        return "Mayo";
                    break;
                    case 6: case "06":
                        return "Junio";
                    break;
                    case 7: case "07":
                        return "Julio";
                    break;
                    case 8: case "08":
                        return "Agosto";
                    break;
                    case 9: case "09":
                        return "Septiembre";
                    break;
                    case 10: case "10":
                        return "Octubre";
                    break;
                    case 11: case "11":
                        return "Noviembre";
                    break;
                    case 12: case "12":
                        return "Diciembre";
                    break;
                    default:
                        return month;
                    break;
                }
                return "";
        }
    </script>

@endsection