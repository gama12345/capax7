<head>
    <title>Capax7 - Registrar donación</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/RegisterDonation.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="registerDonation" class="registerDonation">
        <div class="title"><h1>REGISTRAR DONATIVO</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        
        <div class="registerDonation__sections">
            <fieldset class="registerDonation__donorInfoSection">
                    <legend>Nuevo registro</legend>
                    <form method="post" action="{{ route('registerDonation') }}" autocomplete="off" onKeyPress="return (event.keyCode !== 13)">
                        @csrf
                        <input id="id" name="id" hidden value="{{ old('id') }}"/>

                        <div class="registerDonation__inputData">
                                <input id="autoComplete" tabindex="1" name="razon_social" type="text" title="Razón social del donante" value="{{ old('razon_social') }}"/>
                                <label title="Razón social del donante">Donante *</label>
                        </div>

                        <div class="registerDonation__inputData">
                                <input name="fecha" type="text" value="{{ $date }}" disabled/>
                                <label>Fecha </label>
                        </div>

                        <div class="registerDonation__inputData">
                                <input name="cantidad" type="text" value="{{ old('cantidad') }}" placeholder="0.00"/>
                                <label>Monto *</label>
                        </div>


                        <div class="registerDonation__dataMsg">
                            * Información requerida
                        </div>

                        <button class="registerDonation__donorInfoBtn">
                            <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                                <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                            </svg>
                            <span>Registrar donante</span>
                        </button>
                        <button type="button" class="registerDonation__donorBackBtn" onclick="location.href = '{{ route('showDonorsMenu') }}'">
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
        window.onload = function() {
            document.getElementById('registerDonation').className += " slideEffect";
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
                document.getElementById('id').value = feedback.selection.value.id;
                document.getElementById("autoComplete").value = feedback.selection.value.razon_social;
            }             
        });
    </script>

@endsection