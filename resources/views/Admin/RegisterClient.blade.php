<head>
    <title>Capax7 - Registrar nuevo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Admin/RegisterClient.css') }}">
</head>
@extends('Admin.NavBar')
@section('content')
    <div id="registerClient" class="registerClient">
        <div class="title"><h2>NUEVO REGISTRO</h2></div>
        @if($errors->any())
            <div class="error">{{  $errors->first()  }}</div>
        @endif
        @if(Session::has('success'))
            <div class="success">{{  Session::get('success')  }}</div>
        @endif
        <form method="post" action="{{ route('registerClient') }}" enctype="multipart/form-data">
        @csrf
            <fieldset >
                <legend>Información básica</legend>
                <label for="razon_social">
                    <span>Razón social <span class="required">*</span></span>
                    <input type="text" class="form-field" name="razon_social" value="{{old('razon_social')}}"/>
                </label>
                <label>
                    <span tyle="max-width: 30%">RFC <span class="required">*</span></span>
                    <input type="text" class="form-field" name="rfc"  style="max-width: 30%" value="{{old('rfc')}}"/>
                    
                    <span class="doc_pdf" >RFC<span class="required">*</span></span>
                    <input type="file" name="doc_rfc" accept="application/pdf" style="max-width: 30%"/>
                </label>
            </fieldset>
            
            <fieldset >
                <legend>Información de contacto</legend>
                <label for="email">
                    <span>Email <span class="required">*</span></span>
                    <input type="email" class="form-field" name="email" value="{{old('email')}}"/>
                </label>
                <label>
                    <span style="max-width: 30%">Teléfono <span class="required">*</span></span>
                    <input type="text" class="form-field" name="telefono"  style="max-width: 30%" value="{{old('telefono')}}"/>
                    
                    <span style="margin-left: 5%; margin-right: -5%;">Celular</span>
                    <input type="text" class="form-field" name="celular"  style="max-width: 30%" value="{{old('celular')}}"/>
                </label>
                <label for="pagina_web">
                    <span>Página web</span>
                    <input type="text" class="form-field" name="pagina_web" value="{{old('pagina_web')}}" placeholder="https://www.mi-pagina-web.com"/>
                </label>
                <label for="redes_sociales">
                    <span>Redes sociales</span>
                    <input type="text" class="form-field" name="facebook" placeholder="Facebook" style="width: 25%; margin-right: 3%;" value="{{old('facebook')}}"/>
                    <input type="text" class="form-field" name="twitter" placeholder="Twitter" style="width: 25%; margin-right: 3%;" value="{{old('twitter')}}"/>
                    <input type="text" class="form-field" name="instagram" placeholder="Instagram" style="width: 25%;" value="{{old('instagram')}}"/>
                </label>
            </fieldset>
            
            <fieldset>
                <legend>Información administrativa</legend>
                <label>
                    <span title="Tipo de persona">Persona <span class="required">*</span></span>
                    <select type="text" class="form-field" name="tipo_persona" style="max-width: 30%;" onchange="orgCheckType(this.value)">
                        <option value="Fisica">Fisica</option>
                        <option value="Moral">Moral</option>
                    </select>

                    <span id="tipo_org_span" class="org" hidden>Tipo <span class="required">*</span></span>
                    <select id="tipo_org" hidden type="text" class="form-field" name="tipo_org" style="max-width: 30%;" onchange="isLucrative(this.value)">
                        <option value="Si">Lucrativa</option>
                        <option value="No">No lucrativa</option>
                    </select>
                </label>

                <label>
                    <span tyle="max-width: 30%" title="Representante legal">R. Legal <span class="required">*</span></span>
                    <input type="text" class="form-field" name="r_legal"  style="max-width: 30%" value="{{old('r_legal')}}"/>
                    
                    <span class="doc_pdf" >R. Legal <span class="required">*</span></span>
                    <input type="file" name="doc_r_legal" accept="application/pdf" style="max-width: 30%">
                </label>

                <label>
                    <span tyle="max-width: 30%" title="Num. de cuenta bancaria">Cta. bancaria <span class="required">*</span></span>
                    <input type="text" class="form-field" name="cta_bancaria"  style="max-width: 30%" value="{{old('cta_bancaria')}}"/>
                    
                    <span class="doc_pdf" >Cta. <span class="required">*</span></span>
                    <input type="file" name="doc_cta_bancaria" accept="application/pdf" style="max-width: 30%">
                </label>

                <label>
                    <span tyle="max-width: 30%" title="Num. de seguro">IMSS <span class="required">*</span></span>
                    <input type="text" class="form-field" name="imss"  style="max-width: 30%" value="{{old('imss')}}"/>
                    
                    <span class="doc_pdf" >IMSS <span class="required">*</span></span>
                    <input type="file" name="doc_imss" accept="application/pdf" style="max-width: 30%">
                </label>

                <label>
                    <span tyle="max-width: 30%" title="Agente Capacitador Externo">ACE <span class="required">*</span></span>
                    <input type="text" class="form-field" name="ace_stps"  style="max-width: 30%" value="{{old('ace_stps')}}"/>
                    
                    <span class="doc_pdf" >ACE <span class="required">*</span></span>
                    <input type="file" name="doc_ace_stps" accept="application/pdf" style="max-width: 30%">
                </label>
            </fieldset>

            
            <fieldset id="lucrative" hidden>
                <legend>Persona moral lucrativa</legend>
                <!--Moral con fin de lucro-->
                <label>
                    <span tyle="max-width: 30%" title="Acta constitutiva">Acta <span class="required">*</span></span>
                    <input type="file" name="doc_acta_constitutiva_lucrativa" accept="application/pdf" style="width: 80%; max-width: 30%">
                    
                    <span class="doc_pdf" title="Folio Registral Electrónico">Folio <span class="required">*</span></span>
                    <input type="file" name="doc_folio_reg_electronico_lucrativa" accept="application/pdf" style="max-width: 30%">
                </label>
            </fieldset>

            <fieldset id="non-lucrative" hidden>
                <legend>Persona moral no lucrativa</legend>
                <!--Moral sin fin de lucro-->
                <label>
                    <span tyle="max-width: 30%" title="Acta constitutiva">Acta <span class="required">*</span></span>
                    <input type="file" name="doc_acta_constitutiva_no_lucrativa" accept="application/pdf" style="width: 80%; max-width: 30%">
                    
                    <span class="doc_pdf" title="Folio Registral Electrónico">Folio <span class="required">*</span></span>
                    <input type="file" name="doc_folio_reg_electronico_no_lucrativa" accept="application/pdf" style="max-width: 30%">
                </label>
                <label>
                    <span tyle="max-width: 30%" title="Autorización Donataria Fiscal">ADF <span class="required">*</span></span>
                    <input type="file" name="doc_autorizacion_fiscal" accept="application/pdf" style="width: 80%; max-width: 30%">
                    
                    <span class="doc_pdf" title="Registro de marca">Marca<span class="required">*</span></span>
                    <input type="file" name="doc_reg_marca" accept="application/pdf" style="max-width: 30%">
                </label>
                <label>
                    <span tyle="max-width: 30%" title="Clave Única de Inscripción al Registro de OSC">Cluni <span class="required">*</span></span>
                    <input type="file" name="doc_cluni" accept="application/pdf" style="width: 80%; max-width: 30%">
                    
                    <span class="doc_pdf" title="Multilaterales">Multi<span class="required">*</span></span>
                    <input type="file" name="doc_multilaterales" accept="application/pdf" style="max-width: 30%">
                </label>
            </fieldset>
            <button>Guardar</button>
        </form>
    </div>

    <script>
        window.onload = function() {
            document.getElementById('registerClient').className += " slideEffect";
        }
        function orgCheckType(type){
            if(type === 'Moral'){
                document.getElementById('tipo_org').removeAttribute("hidden");
                document.getElementById('tipo_org_span').removeAttribute("hidden");
                if(document.getElementById('tipo_org').value == "Si"){
                    document.getElementById('lucrative').removeAttribute("hidden");
                    document.getElementById('non-lucrative').setAttribute("hidden",true);
                }else{
                    document.getElementById('non-lucrative').removeAttribute("hidden");
                    document.getElementById('lucrative').setAttribute("hidden",true);
                }
            }else{
                document.getElementById('tipo_org').setAttribute("hidden",true);
                document.getElementById('tipo_org_span').setAttribute("hidden",true);
                document.getElementById('lucrative').setAttribute("hidden",true);
                document.getElementById('non-lucrative').setAttribute("hidden",true);
            }
        }
        function isLucrative(type){
            if(type == 'Si'){
                document.getElementById('lucrative').removeAttribute("hidden");
                document.getElementById('non-lucrative').setAttribute("hidden",true);
            }else{
                document.getElementById('non-lucrative').removeAttribute("hidden");
                document.getElementById('lucrative').setAttribute("hidden",true);
            }
        }
    </script>
@endsection