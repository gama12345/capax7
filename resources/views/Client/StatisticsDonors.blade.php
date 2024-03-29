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
            <div id="statisticsDonors__fechas">
                <div class="statisticsDonors__typeGraph">
                    <label for="years" >Año:</label>
                    <div class="select">
                        <select id="years" onchange="setYear()" >
                            
                        </select>
                    </div>
                </div>
                <div class="statisticsDonors__typeGraph">
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
                <form method="post" action="{{ route('showDetailedDonorsMonthYear') }}">
                @csrf
                <input id="mes" name="month" hidden/>
                <input id="año" name="year" hidden/>
                <button class="btn" type="submit">Ver gráfico</button>
                </form>
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
        
        var añoRegistro = parseInt(@json($year));
        var añoActual = parseInt(@json($currentYear));
        var mesActual = @json($currentMonth);
        var donaciones = @json($donaciones);
        var mesSeleccionado = @json($selectedMonth);
        var añoSeleccionado = @json($selectedYear);

        document.getElementById('months').value = mesActual;
        while(añoActual >= añoRegistro){
            document.getElementById('years').innerHTML += "<option>"+añoActual+"</option>";
            añoActual--;
        }

        if(donaciones != ""){
            makeGraph();
        }
        
        if(mesSeleccionado != "" && añoSeleccionado != ""){
            document.getElementById("months").value = mesSeleccionado;
            document.getElementById("years").value = añoSeleccionado;
        }
        document.getElementById("mes").value = document.getElementById("months").value;
        document.getElementById("año").value = document.getElementById("years").value;
        
        

        function makeGraph(){
            array = donaciones;
            donantes = new Array();
            fullNames = new Array();
            montos = new Array();
            array.forEach(donor => {
                    nombre = donor.razon_social;
                    if(donor.razon_social.length > 15){
                        nombre = donor.razon_social.substring(0,15)+"...";
                    }
                    donantes.push(nombre);
                    fullNames.push(donor.razon_social);
                    montos.push(donor.total);                    
                });            
            fullNames.reverse(); montos.reverse(); donantes.reverse();
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
                        text: "Mejores donadores"
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
            
    </script>

@endsection