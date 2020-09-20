<head>
    <title>Capax7 - Registrar nuevo</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/Admin/RegisterClient.css') }}">
</head>
@extends('Admin.NavBar')
@section('content')
    <div class="registerClient">
        <form method="post">
            <fieldset >
                <legend>Información básica</legend>
                <label for="razon_social">
                    <span>Razón social <span class="required">*</span></span>
                    <input type="text" class="form-field" name="razon_social"/>
                </label>
                <label>
                    <span tyle="max-width: 30%">RFC <span class="required">*</span></span>
                    <input type="text" class="form-field" name="rfc"  style="max-width: 30%"/>
                    
                    <span class="doc_pdf" >RFC<span class="required">*</span></span>
                    <input type="file" name="doc_rfc" accept="application/pdf" style="max-width: 30%">
                </label>
            </fieldset>
            
            <fieldset >
                <legend>Información de contacto</legend>
                <label for="email">
                    <span>Email <span class="required">*</span></span>
                    <input type="email" class="form-field" name="email" />
                </label>
                <label>
                    <span style="max-width: 30%">Teléfono <span class="required">*</span></span>
                    <input type="text" class="form-field" name="telefono"  style="max-width: 30%"/>
                    
                    <span style="margin-left: 5%; margin-right: -5%;">Celular</span>
                    <input type="text" class="form-field" name="celular"  style="max-width: 30%"/>
                </label>
                <label for="pagina_web">
                    <span>Página web</span>
                    <input type="text" class="form-field" name="pagina_web" />
                </label>
                <label for="redes_sociales">
                    <span>Redes sociales</span>
                    <input type="text" class="form-field" name="facebook" placeholder="Facebook" style="width: 25%; margin-right: 3%;"/>
                    <input type="text" class="form-field" name="twitter" placeholder="Twitter" style="width: 25%; margin-right: 3%;"/>
                    <input type="text" class="form-field" name="instagram" placeholder="Instagram" style="width: 25%;"/>
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
                    <select id="tipo_org" hidden type="text" class="form-field" name="tipo_org" style="max-width: 30%;">
                        <option value="Si">Lucrativa</option>
                        <option value="No">No lucrativa</option>
                    </select>
                </label>

                <label>
                    <span tyle="max-width: 30%" title="Representante legal">R. Legal <span class="required">*</span></span>
                    <input type="text" class="form-field" name="r_legal"  style="max-width: 30%"/>
                    
                    <span class="doc_pdf" >R. Legal <span class="required">*</span></span>
                    <input type="file" name="doc_r_legal" accept="application/pdf" style="max-width: 30%">
                </label>

                <label>
                    <span tyle="max-width: 30%" title="Num. de cuenta bancaria">Cta. bancaria <span class="required">*</span></span>
                    <input type="text" class="form-field" name="cta_bancaria"  style="max-width: 30%"/>
                    
                    <span class="doc_pdf" >Cta. <span class="required">*</span></span>
                    <input type="file" name="doc_cta_bancaria" accept="application/pdf" style="max-width: 30%">
                </label>

                <label>
                    <span tyle="max-width: 30%" title="Num. de seguro">IMSS <span class="required">*</span></span>
                    <input type="text" class="form-field" name="imss"  style="max-width: 30%"/>
                    
                    <span class="doc_pdf" >IMSS <span class="required">*</span></span>
                    <input type="file" name="doc_imss" accept="application/pdf" style="max-width: 30%">
                </label>

                <label>
                    <span tyle="max-width: 30%" title="Agente Capacitador Externo">ACE <span class="required">*</span></span>
                    <input type="text" class="form-field" name="ace_stps"  style="max-width: 30%"/>
                    
                    <span class="doc_pdf" >ACE <span class="required">*</span></span>
                    <input type="file" name="doc_ace_stps" accept="application/pdf" style="max-width: 30%">
                </label>
            </fieldset>

            
            <fieldset>
                <legend>Persona moral lucrativa</legend>
                <!--Moral con fin de lucro-->
                <label>
                    <span tyle="max-width: 30%" title="Acta constitutiva">Acta <span class="required">*</span></span>
                    <input type="file" name="doc_acta_constitutiva" accept="application/pdf" style="max-width: 30%">
                    
                    <span class="doc_pdf" title="Folio Registral Electrónico">Folio <span class="required">*</span></span>
                    <input type="file" name="doc_folio_reg_electronico" accept="application/pdf" style="max-width: 30%">
                </label>
            </fieldset>

            <fieldset>
                <legend>Persona moral no lucrativa</legend>
                <!--Moral sin fin de lucro-->
                <label>
                    <span tyle="max-width: 30%" title="Autorización Donataria Fiscal">ADF <span class="required">*</span></span>
                    <input type="file" name="doc_autoriacion_fiscal" accept="application/pdf" style="max-width: 30%">
                    
                    <span class="doc_pdf" title="Registro de marca">Marca<span class="required">*</span></span>
                    <input type="file" name="doc_reg_marca" accept="application/pdf" style="max-width: 30%">
                </label>
                <label>
                    <span tyle="max-width: 30%" title="Cluni">Cluni <span class="required">*</span></span>
                    <input type="file" name="doc_cluni" accept="application/pdf" style="max-width: 30%">
                    
                    <span class="doc_pdf" title="Multilaterales">Multi<span class="required">*</span></span>
                    <input type="file" name="doc_multilaterales" accept="application/pdf" style="max-width: 30%">
                </label>
            </fieldset>
        </form>
    </div>

    <script>
        function orgCheckType(type){
            if(type === 'Moral'){
                document.getElementById('tipo_org').removeAttribute("hidden");
                document.getElementById('tipo_org_span').removeAttribute("hidden");
            }else{
                document.getElementById('tipo_org').setAttribute("hidden",true);
                document.getElementById('tipo_org_span').setAttribute("hidden",true);
            }
        }
    </script>
@endsection