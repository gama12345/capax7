<head>
    <title>Capax7 - Ver donantes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/Donors.css') }}">

</head>
@extends('Client.NavBar')
@section('content')
    <div id="donors" class="Donors">
        <div class="title"><h1>VER DONANTE</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        
        <div class="donors__sections">
            <fieldset class="donors__donorInfoSection">
                    <legend>Información</legend>
                    <form method="post" action="{{ route('updateDonors') }}" autocomplete="off" onKeyPress="return (event.keyCode !== 13)">
                        @csrf
                        <input id="id" name="id" hidden value="{{ old('id') }}"/>
                        <div class="donors__inputData">
                                <input id="autoComplete" tabindex="1" name="razon_social" type="text" value="{{ old('razon_social') }}"/>
                                <label>Razón social *</label>
                        </div>

                        <div class="donors__inputData">
                            <select id="tipo_persona" type="text" id="select_tipo_persona" name="tipo_persona" disabled>
                                <option value="Fisica" @if (old("tipo_persona") == "Fisica") {{ 'selected' }} @endif>Fisica</option>
                                <option value="Moral" @if (old("tipo_persona") == "Moral") {{ 'selected' }} @endif>Moral</option>
                            </select>                        
                            <label>Persona</label>
                        </div>

                        <div class="donors__inputData">
                                <input id="rfc" name="rfc" type="text" disabled value="{{ old('rfc') }}"/>
                                <label>RFC *</label>
                        </div>

                        <div class="donors__inputData">
                                <input id="email" name="email" type="text" disabled value="{{ old('email') }}"/>
                                <label>Email *</label>
                        </div>

                        <div class="donors__inputData">
                                <input id="nacionalidad" name="nacionalidad" type="text" disabled value="{{ old('nacionalidad') }}"/>
                                <label>Nacionalidad *</label>
                        </div>

                        <div class="donors__inputData">
                                <input id="telefono" name="telefono" type="text" disabled value="{{ old('telefono') }}"/>
                                <label>Teléfono *</label>
                        </div>

                        <div class="donors__inputData">
                                <input id="celular" name="celular" type="text" disabled value="{{ old('celular') }}"/>
                                <label>Celular</label>
                        </div>
                        <div class="donors__inputData">
                                <input id="calle" name="calle" type="text" value="{{ old('calle') }}" placeholder="Calle..."/>
                                <label title="Calle del domicilio">Calle *</label>
                        </div>
                        <div class="donors__inputData">
                                <input id="num_calle" name="num_calle" type="text" value="{{ old('num_calle') }}" placeholder="Número de Calle..."/>
                                <label title="Número de la calle del domicilio">Número de calle *</label>
                        </div>
                        <div class="donors__inputData">
                                <input id="colonia" name="colonia" type="text" value="{{ old('colonia') }}" placeholder="Colonia..."/>
                                <label title="Colonia del domicilio">Colonia *</label>
                        </div>
                        <div class="donors__inputData">
                                <input id="codigo_postal" name="codigo_postal" type="text" value="{{ old('codigo_postal') }}" placeholder="Código postal..."/>
                                <label title="Código postal del domicilio">Código postal *</label>
                        </div>
<!--
                        <div class="donors__inputData">
                                <input id="domicilio" name="domicilio" type="text" disabled value="{{ old('domicilio') }}"/>
                                <label title="Calle, número, colonia y código postal">Domicilio *</label>
                        </div>
-->
                        <div class="donors__dataMsg">
                            * Información requerida
                        </div>

                        <button id="btnGuardar" class="donors__donorInfoBtn" disabled>
                            <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                            </svg>
                            <span>Guardar cambios</span>
                        </button>
                        <button type="button" class="donors__donorBackBtn" onclick="location.href = '{{ route('showDonorsMenu') }}'">
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

    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/js/autoComplete.min.js"></script>

    <script>
        //On window load show animation
        window.onload = function() {
            document.getElementById('donors').className += " slideEffect";
        }
        //if there was an error while updating, show inputs and values
        if(document.getElementById('id').value != ""){
            document.getElementById('rfc').disabled = false;
            document.getElementById('tipo_persona').disabled = false;
            document.getElementById('nacionalidad').disabled = false;
            document.getElementById('email').disabled = false;
            document.getElementById('telefono').disabled = false;
            document.getElementById('celular').disabled = false;
            document.getElementById('calle').disabled = false;
            document.getElementById('num_calle').disabled = false;
            document.getElementById('colonia').disabled = false;
            document.getElementById('codigo_postal').disabled = false;
            document.getElementById('btnGuardar').disabled = false;
        }
        //Load autoComplete razon_social
        new autoComplete({
            data: {                              // Data src [Array, Function, Async] | (REQUIRED)
                src: @json($donors),
                key: ["razon_social"],
                cache: false
            },
            sort: (a, b) => {                    // Sort rendered results ascendingly | (Optional)
                if (a.match < b.match) return -1;
                if (a.match > b.match) return 1;
                return 0;
            },
            placeHolder: "Escriba la razón social del donante...",     // Place Holder text                 | (Optional)
            selector: "#autoComplete",           // Input field selector              | (Optional)
            threshold: 0,                        // Min. Chars length to start Engine | (Optional)
            debounce: 0,                       // Post duration for engine to start | (Optional)
            searchEngine: "strict",              // Search Engine type/mode           | (Optional)
            resultsList: {                       // Rendered results list object      | (Optional)
                render: true,
                /* if set to false, add an eventListener to the selector for event type
                "autoComplete" to handle the result */
                container: source => {
                    source.setAttribute("id", "autoComplete_list");
                },
                destination: document.querySelector("#autoComplete"),
                position: "afterend",
                element: "ul"
            },
            maxResults: 3,                         // Max. number of rendered results | (Optional)
            highlight: true,                       // Highlight matching results      | (Optional)
            resultItem: {                          // Rendered result item            | (Optional)
                content: (data, source) => {
                    source.innerHTML = data.match;
                },
                element: "li"
            },
            noResults: () => {                     // Action script on noResults      | (Optional)
                const result = document.createElement("li");
                result.setAttribute("class", "no_result");
                result.setAttribute("tabindex", "1");
                result.innerHTML = "Sin coincidencias";
                document.querySelector("#autoComplete_list").appendChild(result);
                console.log("No results");
            },
            onSelection: feedback => {             // Action script onSelection event | (Optional)
                //console.log(feedback.selection.value.razon_social);
                document.getElementById('rfc').disabled = false;
                document.getElementById('tipo_persona').disabled = false;
                document.getElementById('nacionalidad').disabled = false;
                document.getElementById('email').disabled = false;
                document.getElementById('telefono').disabled = false;
                document.getElementById('celular').disabled = false;
                document.getElementById('calle').disabled = false;
                document.getElementById('num_calle').disabled = false;
                document.getElementById('colonia').disabled = false;
                document.getElementById('codigo_postal').disabled = false;
                document.getElementById('btnGuardar').disabled = false;

                document.getElementById("autoComplete").value = feedback.selection.value.razon_social;
                document.getElementById('tipo_persona').value = feedback.selection.value.tipo_persona;
                document.getElementById('rfc').value = feedback.selection.value.rfc;
                document.getElementById('nacionalidad').value = feedback.selection.value.nacionalidad;
                document.getElementById('email').value = feedback.selection.value.email;
                document.getElementById('telefono').value = feedback.selection.value.telefono;
                document.getElementById('celular').value = feedback.selection.value.celular;
                elementosDomicilio = feedback.selection.value.domicilio.split("%.%");
                document.getElementById('calle').value = elementosDomicilio[0];
                document.getElementById('num_calle').value = elementosDomicilio[1];
                document.getElementById('colonia').value = elementosDomicilio[2];
                document.getElementById('codigo_postal').value = elementosDomicilio[3];
                document.getElementById('id').value = feedback.selection.value.id;
            }             
        });
    </script>

@endsection