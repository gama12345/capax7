<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/Home.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="home" class="home">
        <div class="title"><h1>MIS DATOS</h1></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        <div class="home__sections">
            <fieldset class="home__adminInfoSection">
                <legend>Administrativos</legend>
                <form method="post" action="{{ route('updateAdministrativeInformation') }}" autocomplete="off">
                    @csrf
                    <div class="home__inputData">
                            <input name="razon_social" type="text" value="{{ $datos->razon_social }}"/>
                            <label>Razón social</label>
                    </div>

                    <div class="home__inputData">
                            <input name="rfc" type="text" value="{{ $datos->rfc }}"/>
                            <label>RFC</label>
                    </div>

                    <div class="home__inputData">
                        <select type="text" id="select_tipo_persona" name="tipo_persona" onchange="orgCheckType(this.value)" title="Cambiar su persona puede afectar a sus documentos guardados">
                            <option value="Fisica">Fisica</option>
                            <option value="Moral">Moral</option>
                        </select>
                        <label>Persona</label>
                    </div>

                    <div id="tipo_org" class="home__inputData" hidden>
                        <select type="text" id="select_tipo_org" name="tipo_org" onchange="isLucrative(this.value)">
                            <option value="Si">Lucrativa</option>
                            <option value="No">No lucrativa</option>
                        </select>
                        <label>Tipo</label>
                    </div>

                    <div class="home__inputData">
                            <input name="r_legal" type="text" value="{{ $datos->r_legal }}"/>
                            <label>Representante legal</label>
                    </div>

                    <div class="home__inputData">
                            <input name="cta_bancaria" type="text" value="{{ $datos->cta_bancaria }}"/>
                            <label>Cuenta bancaria</label>
                    </div>

                    <div class="home__inputData">
                            <input name="imss" type="text" value="{{ $datos->imss }}"/>
                            <label>N° seguro</label>
                    </div>

                    <div class="home__inputData">
                            <input name="ace_stps" type="text" value="{{ $datos->ace_stps }}"/>
                            <label>Agente Capacitador</label>
                    </div>

                    <button class="home__adminInfoBtn">
                        <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                            <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                            <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                        </svg>
                        <span>Guardar cambios</span>
                    </button>
                </form>
            </fieldset>

            <fieldset class="home__gralInfoSection">
                <legend>Generales</legend>
                <form method="post" action="{{ route('updateGeneralInformation') }}" autocomplete="off">
                    @csrf
                    <div class="home__inputData">
                            <input name="email" type="text" value="{{ $datos->email }}"/>
                            <label>Email</label>
                    </div>

                    <div class="home__inputData">
                            <input name="contraseña" type="password" value="******"/>
                            <label>Contraseña</label>
                    </div>

                    <div class="home__inputData" >
                            <input name="telefono" type="text" value="{{ $datos->telefono }}"/>
                            <label>Teléfono</label>
                    </div>

                    <div class="home__inputData">
                            <input name="celular" type="text" value="{{ $datos->celular }}"/>
                            <label>Celular</label>
                    </div>

                    <div class="home__inputData">
                        <input name="pagina_web" type="text" value="{{ $datos->pagina_web }}"/>                            
                        <a href="{{ $datos->pagina_web }}">Página web</a>
                    </div>

                    <div class="home__socialNetworkItem">
                        <input name="facebook" type="text" value="{{ $datos->facebook }}"/>
                        <a href='https://www.facebook.com/{{$datos->facebook}}'>Facebook</a>
                    </div>
                    <div class="home__socialNetworkItem">
                        <input name="twitter" type="text" value="{{ $datos->twitter }}"/>
                        <a href='https://www.twitter.com/{{$datos->twitter}}'>Twitter</a>
                    </div>
                    <div class="home__socialNetworkItem">
                        <input name="instagram" type="text" value="{{ $datos->instagram }}"/>
                        <a href='https://www.instagram.com/{{$datos->instagram}}'>Instagram</a>
                    </div>

                    <button class="home__adminInfoBtn">
                        <svg width="150px" height="25px" viewBox="0 0 150 25" class="border">
                            <polyline points="149,1 149,24 1,24 1,1 149,1" class="bg-line" />
                            <polyline points="149,1 149,24 1,24 1,1 149,1" class="hl-line" />
                        </svg>
                        <span>Guardar cambios</span>
                    </button>
                </form>
            </fieldset>

            <fieldset class="home__docSection">
                <legend>Documentos</legend>
                <!--Persona Fisica-->
                <div class="home__docSectionRow" >
                    <div class="home__docContainer">
                        <input type="image" id="rfc" class="home__docImg" src="/Images/rfc-logo.png" data-toggle="tooltip" title="RFC"/>
                        <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $rfc->nombre }}')">Ver</button>
                        <button class="home__btnChange" onclick="showModal('rfc')">Cambiar</button>
                    </div>
                    <div class="home__docContainer">
                        <input type="image" id="r_legal" class="home__docImg" src="/Images/r-legal-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Representante legal"/>
                        <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $r_legal->nombre }}')">Ver</button>
                        <button class="home__btnChange" onclick="showModal('r_legal')">Cambiar</button>                    
                    </div>
                    <div class="home__docContainer">
                        <input type="image" id="cta_bancaria" class="home__docImg" src="/Images/cta-bancaria-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Cuenta bancaria"/>
                        <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $cta_bancaria->nombre }}')">Ver</button>
                        <button class="home__btnChange" onclick="showModal('cta_bancaria')">Cambiar</button>                    
                    </div>
                </div>
                <div class="home__docSectionRow" >
                    <div class="home__docContainer">
                        <input type="image" id="imss" class="home__docImg" src="/Images/imss-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="N° Seguro (IMSS)"/>
                        <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $imss->nombre }}')">Ver</button>
                        <button class="home__btnChange" onclick="showModal('imss')">Cambiar</button>                    
                    </div>
                    <div class="home__docContainer">
                        <input type="image" id="ace_stps" class="home__docImg" src="/Images/ace-stps-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Agente Capacitador Externo"/>
                        <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $ace_stps->nombre }}')">Ver</button>
                        <button class="home__btnChange" onclick="showModal('ace_stps')">Cambiar</button>                    
                    </div>
                </div>

                <div id="lucrative" class="home__docSectionRow oculto">
                    <div class="home__docContainer">
                        <input type="image" id="acta_constitutiva_lucrativa" class="home__docImg" src="/Images/acta-constitutiva-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Acta Constitutiva"/>
                        <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $acta_constitutiva->nombre ?? 'doc-no-found.pdf' }}')">Ver</button>
                        <button class="home__btnChange" onclick="showModal('acta_constitutiva_lucrativa')">Cambiar</button>                    
                    </div>
                    <div class="home__docContainer">
                        <input type="image" id="folio_reg_electronico_lucrativa" class="home__docImg" src="/Images/folio-reg-electronico-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Folio Registral Electrónico"/>
                        <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $folio_reg_electronico->nombre ?? 'doc-no-found.pdf' }}')">Ver</button>
                        <button class="home__btnChange" onclick="showModal('folio_reg_electronico_lucrativa')">Cambiar</button>                    
                    </div>
                </div>
                <div id="non-lucrative" class="oculto">
                    <div class="home__docSectionRow" >
                        <div class="home__docContainer">
                            <input type="image" id="acta_constitutiva_no_lucrativa" class="home__docImg" src="/Images/acta-constitutiva-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Acta Constitutiva"/>
                            <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $acta_constitutiva->nombre ?? 'doc-no-found.pdf' }}')">Ver</button>
                            <button class="home__btnChange" onclick="showModal('acta_constitutiva_no_lucrativa')">Cambiar</button>                    
                        </div>
                        <div class="home__docContainer">
                            <input type="image" id="folio_reg_electronico_no_lucrativa" class="home__docImg" src="/Images/folio-reg-electronico-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Folio Registral Electrónico"/>
                            <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $folio_reg_electronico->nombre ?? 'doc-no-found.pdf' }}')">Ver</button>
                            <button class="home__btnChange" onclick="showModal('folio_reg_electronico_no_lucrativa')">Cambiar</button>                    
                        </div>
                        <div class="home__docContainer">
                            <input type="image" id="autorizacion_fiscal" class="home__docImg" src="/Images/autorizacion-fiscal-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Autorización Fiscal"/>
                            <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $autorizacion_fiscal->nombre ?? 'doc-no-found.pdf' }}')">Ver</button>
                            <button class="home__btnChange" onclick="showModal('autorizacion_fiscal')">Cambiar</button>                    
                        </div>
                    </div>
                    <div class="home__docSectionRow" >
                        <div class="home__docContainer">
                            <input type="image" id="reg_marca" class="home__docImg" src="/Images/reg-marca-logo.jpg" onclick="showModal(this.id)" data-toggle="tooltip" title="Registro de Marca"/>
                            <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $reg_marca->nombre ?? 'doc-no-found.pdf' }}')">Ver</button>
                            <button class="home__btnChange" onclick="showModal('reg_marca')">Cambiar</button>                    
                        </div>
                        <div class="home__docContainer">
                            <input type="image" id="cluni" class="home__docImg" src="/Images/cluni-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Clave Única de Inscripción al Registro de OSC"/>
                            <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $cluni->nombre ?? 'doc-no-found.pdf' }}')">Ver</button>
                            <button class="home__btnChange" onclick="showModal('cluni')">Cambiar</button>                    
                        </div>
                        <div class="home__docContainer">
                            <input type="image" id="multilaterales" class="home__docImg" src="/Images/multilaterales-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Multilaterales"/>
                            <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $multilaterales->nombre ?? 'doc-no-found.pdf' }}')">Ver</button>
                            <button class="home__btnChange" onclick="showModal('multilaterales')">Cambiar</button>                    
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <!--Modal change doc-->
        <div id="modal" class="modal">
            <div id="content" class="modal-content">
                <div id="header" class="modal-header" style="background-color: #152889;">
                    <p>Modificar documento</p>
                    <span class="close" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-body">                    
                    <form id="modal-rfc" method="post" action="{{ route('updateDocument',['orgType'=>'Fisica', 'doc'=>'rfc']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo RFC</p>
                        <input type="file" name="doc_rfc" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-r_legal" method="post" action="{{ route('updateDocument',['orgType'=>'Fisica', 'doc'=>'r_legal']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo documento de representante legal</p>
                        <input type="file" name="doc_r_legal" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-cta_bancaria" method="post" action="{{ route('updateDocument',['orgType'=>'Fisica', 'doc'=>'cta_bancaria']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo documento de cuenta de banco</p>
                        <input type="file" name="doc_cta_bancaria" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-imss" method="post" action="{{ route('updateDocument',['orgType'=>'Fisica', 'doc'=>'imss']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo documento de seguro (IMSS)</p>
                        <input type="file" name="doc_imss" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-ace_stps" method="post" action="{{ route('updateDocument',['orgType'=>'Fisica', 'doc'=>'ace_stps']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo agente capacitador</p>
                        <input type="file" name="doc_ace_stps" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-acta_constitutiva_lucrativa" method="post" action="{{ route('updateDocument',['orgType'=>'Moral', 'doc'=>'acta_constitutiva_lucrativa']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nueva acta constitutiva</p>
                        <input type="file" name="doc_acta_constitutiva_lucrativa" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-acta_constitutiva_no_lucrativa" method="post" action="{{ route('updateDocument',['orgType'=>'Moral', 'doc'=>'acta_constitutiva_no_lucrativa']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nueva acta constitutiva</p>
                        <input type="file" name="doc_acta_constitutiva_no_lucrativa" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-folio_reg_electronico_lucrativa" method="post" action="{{ route('updateDocument',['orgType'=>'Moral', 'doc'=>'folio_reg_electronico_lucrativa']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo folio registral</p>
                        <input type="file" name="doc_folio_reg_electronico_lucrativa" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-folio_reg_electronico_no_lucrativa" method="post" action="{{ route('updateDocument',['orgType'=>'Moral', 'doc'=>'folio_reg_electronico_no_lucrativa']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo folio registral</p>
                        <input type="file" name="doc_folio_reg_electronico_no_lucrativa" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-autorizacion_fiscal" method="post" action="{{ route('updateDocument',['orgType'=>'Moral', 'doc'=>'autorizacion_fiscal']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nueva autorizacion fiscal</p>
                        <input type="file" name="doc_autorizacion_fiscal" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-reg_marca" method="post" action="{{ route('updateDocument',['orgType'=>'Moral', 'doc'=>'reg_marca']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo documento de registro de marca</p>
                        <input type="file" name="doc_reg_marca" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-cluni" method="post" action="{{ route('updateDocument',['orgType'=>'Moral', 'doc'=>'cluni']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo documento CLUNI</p>
                        <input type="file" name="doc_cluni" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                    <form id="modal-multilaterales" method="post" action="{{ route('updateDocument',['orgType'=>'Moral', 'doc'=>'multilaterales']) }}" style="display: none" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione su nuevo documento multilaterales</p>
                        <input type="file" name="doc_multilaterales" accept="application/pdf" class="home__docBtn" />
                        <button class="modal-btn">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var datos = @json($datos);
        var openedModal;
        setTypeOrg();
        window.onload = function() {
            document.getElementById('home').className += " slideEffect";
        }
        function orgCheckType(type){
            if(type === 'Moral'){
                document.getElementById('tipo_org').removeAttribute("hidden");
                if(document.getElementById('select_tipo_org').value == "Si"){
                    document.getElementById('lucrative').classList.remove("oculto");
                    document.getElementById('non-lucrative').classList.add("oculto");
                }else{
                    document.getElementById('lucrative').classList.add("oculto");
                    document.getElementById('non-lucrative').classList.remove("oculto");
                }
            }else{
                    document.getElementById('tipo_org').setAttribute("hidden",true);
                    document.getElementById('lucrative').classList.add("oculto");
                    document.getElementById('non-lucrative').classList.add("oculto");
            }
            alert('Cambiar su persona puede afectar a sus documentos guardados\nOprima "Guardar cambios" si esta seguro');
        }
        function isLucrative(type){
            if(type == 'Si'){
                document.getElementById('lucrative').classList.remove("oculto");
                document.getElementById('non-lucrative').classList.add("oculto");
            }else{
                document.getElementById('lucrative').classList.add("oculto");
                document.getElementById('non-lucrative').classList.remove("oculto");
            }
            alert('Cambiar su tipo puede afectar a sus documentos guardados\nOprima "Guardar cambios" si esta seguro');
        }
        function showModal(id){
            document.getElementById('modal').style.display = 'block';
            document.getElementById('modal-'+id).style.display = 'block';
            openedModal = id;
        }
        function closeModal(){
            document.getElementById('modal-'+openedModal).style.display = 'none';
            document.getElementById('modal').style.display = 'none';
        }
        function setTypeOrg(){
            if(datos.tipo_persona === 'Moral'){
                document.getElementById('select_tipo_persona').value = 'Moral';
                document.getElementById('tipo_org').removeAttribute("hidden");
                if(datos.es_lucrativa == 'Si'){
                    document.getElementById('select_tipo_org').value = datos.es_lucrativa;
                    document.getElementById('lucrative').classList.remove("oculto");
                    document.getElementById('non-lucrative').classList.add("oculto");
                }else{
                    document.getElementById('select_tipo_org').value = datos.es_lucrativa;
                    document.getElementById('lucrative').classList.add("oculto");
                    document.getElementById('non-lucrative').classList.remove("oculto");
                }
            }
        }
    </script>
@endsection