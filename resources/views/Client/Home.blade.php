<head>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Client/Home.css') }}">
</head>
@extends('Client.NavBar')
@section('content')
    <div id="home" class="home">
        <div class="title"><h1>MIS DATOS</h1></div>
        <div class="home__sections">
            <fieldset class="home__adminInfoSection">
                <legend>Administrativos</legend>
                <form>
                    <div class="home__inputData">
                            <input name="razon_social" type="text" value="{{ $datos->razon_social }}"/>
                            <label>Razón social</label>
                    </div>

                    <div class="home__inputData">
                            <input name="rfc" type="text" value="{{ $datos->rfc }}"/>
                            <label>RFC</label>
                    </div>

                    <div class="home__inputData">
                        <select type="text" id="select_tipo_persona" name="tipo_persona" onchange="orgCheckType(this.value)">
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

                    <button class="home__adminInfoBtn">Guardar cambios</button>
                </form>
            </fieldset>

            <fieldset class="home__gralInfoSection">
                <legend>Generales</legend>
                <form>
                    <div class="home__inputData">
                            <input name="email" type="text" value="{{ $datos->email }}"/>
                            <label>Email</label>
                    </div>

                    <div class="home__inputData">
                            <input name="contraseña" type="password" value="********"/>
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
                            <label>Página web</label>
                    </div>

                    <div class="home__socialNetworks">
                        <div class="home__socialNetworkItem">
                            <input name="facebook" type="text" value="{{ $datos->facebook }}"/>
                            <a href='https://www.facebook.com/{{$datos->facebook}}'>Facebook</a>
                        </div>
                        <div class="home__socialNetworkItem">
                            <input name="twitter" type="text" value="{{ $datos->twitter }}"/>
                            <a href='https://www.twitter.com/{{$datos->twitter}}'>Twitter</a>
                        </div>
                        <div class="home__socialNetworkItem">
                            <input name="facebook" type="text" value="{{ $datos->instagram }}"/>
                            <a href='https://www.instagram.com/{{$datos->instagram}}'>Instagram</a>
                        </div>
                    </div>
                    <button class="home__gralInfoBtn">Guardar cambios</button>
                </form>
            </fieldset>

            <fieldset class="home__docSection">
                <legend>Documentos</legend>
                <!--Persona Fisica-->
                <div class="home__docSectionRow" >
                    <div class="home__docContainer">
                        <input type="image" id="rfc" class="home__docImg" src="/Images/rfc-logo.png" data-toggle="tooltip" title="RFC"/>
                        <button class="home__btnShow" onclick="window.open('/storage/clients/{{ $datos->razon_social }}/{{ $docs[0]->nombre }}')">Ver</button>
                        <button class="home__btnChange" onclick="showModal('rfc')">Cambiar</button>
                    </div>
                   <!-- <input type="image" id="r_legal" class="home__docImg" src="/Images/r-legal-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Representante legal"/>
                    <input type="image" id="cta_bancaria" class="home__docImg" src="/Images/cta-bancaria-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Cuenta bancaria"/>
                    -->
                </div>
               <!-- <div class="home__docSectionRow" >
                    <input type="image" id="imss" class="home__docImg" src="/Images/imss-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="N° Seguro (IMSS)"/>
                    <input type="image" id="ace_stps" class="home__docImg" src="/Images/ace-stps-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Agente Capacitador Externo"/>
                </div>-->

                <div id="lucrative" class="home__docSectionRow oculto">
                   <!-- <input type="image" id="acta_constitutiva_lucrativa" class="home__docImg" src="/Images/acta-constitutiva-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Acta Constitutiva"/>
                    <input type="image" id="folio_reg_electronico_lucrativa" class="home__docImg" src="/Images/folio-reg-electronico-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Folio Registral Electrónico"/>
-->
                </div>
                <div id="non-lucrative" class="oculto">
                    <!--<div class="home__docSectionRow" >
                        <input type="image" id="acta_constitutiva_no_lucrativa" class="home__docImg" src="/Images/acta-constitutiva-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Acta Constitutiva"/>
                        <input type="image" id="folio_reg_electronico_no_lucrativa" class="home__docImg" src="/Images/folio-reg-electronico-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Folio Registral Electrónico"/>
                        <input type="image" id="autorizacion_fiscal" class="home__docImg" src="/Images/autorizacion-fiscal-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Autorización Fiscal"/>
                    </div>
                    <div class="home__docSectionRow" >
                        <input type="image" id="reg_marca" class="home__docImg" src="/Images/reg-marca-logo.jpg" onclick="showModal(this.id)" data-toggle="tooltip" title="Registro de Marca"/>
                        <input type="image" id="cluni" class="home__docImg" src="/Images/cluni-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Clave Única de Inscripción al Registro de OSC"/>
                        <input type="image" id="multilaterales" class="home__docImg" src="/Images/multilaterales-logo.png" onclick="showModal(this.id)" data-toggle="tooltip" title="Multilaterales"/>
                    </div>-->
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
                    <p>Seleccione su nuevo documento</p>
                    <input type="text" class="form-control" id="emailRecuperacion" placeholder="Ingrese su email..." autocomplete="off">
                    <center><button type="button" class="btn" onclick="" style="background-color: #08095f; color: white; margin-bottom: 8px; margin-top: 10px; font-size: 14px">Guardar</button></center>
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
        }
        function isLucrative(type){
            if(type == 'Si'){
                document.getElementById('lucrative').classList.remove("oculto");
                document.getElementById('non-lucrative').classList.add("oculto");
            }else{
                document.getElementById('lucrative').classList.add("oculto");
                document.getElementById('non-lucrative').classList.remove("oculto");
            }
        }
        function showModal(id){
            document.getElementById('modal').style.display = 'block';
            openedModal = id;
        }
        function closeModal(){
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