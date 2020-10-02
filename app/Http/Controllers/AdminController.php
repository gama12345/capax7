<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Client;
use App\Models\Document;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{

    //NavBar menu views
    public function showRegisterClient(){
        if(auth('admin')->check()){
            return view('Admin.RegisterClient');
        }else{
            return redirect()->route('main');
        }
    }
    public function showStatistics(){
        if(auth('admin')->check()){
            $currentDate = Carbon::now();
            $year = $currentDate->format('yy');
            $month = $currentDate->format('m');
            $topDonationsClients = DB::select(DB::raw('select cliente, razon_social, sum(cantidad) as total from donations inner join clients on cliente = clients.id group by cliente order by total desc limit 5'));
            $topAnualDonationsClients = DB::select(DB::raw('select cliente, razon_social, sum(cantidad) as total from donations inner join clients on cliente = clients.id where fecha >= "'.$year.'-01-01" and fecha <= "'.$year.'-12-31" group by cliente order by total desc limit 5'));
            $topMonthDonationsClients = DB::select(DB::raw('select cliente, razon_social, sum(cantidad) as total from donations inner join clients on cliente = clients.id where fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" group by cliente order by total desc limit 5'));

            return view('Admin.Statistics')->with('topDonationsClients',$topDonationsClients)
                    ->with('topAnualDonationsClients',$topAnualDonationsClients)
                    ->with('topMonthDonationsClients',$topMonthDonationsClients);
        }else{
            return redirect()->route('main');
        }
    }

    public function updateGeneralInformation(Request $request)
    {

    }
    public function updateAdministrativeInformation(Request $request)
    {
        
    }
    public function registerClient(Request $request){
        //Validation
        $validation = $request->validate([
            'razon_social' => ['required','unique:clients', 'max:200'],
            'rfc' => ['required','unique:clients', 'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/'],
            'doc_rfc' => ['required', 'mimetypes:application/pdf', 'max:2048'],
            'email' => ['required','email','unique:clients'],
            'telefono' => ['required','regex:/^[0-9]{10}/'],
            'celular' => ['nullable','regex:/^[0-9]{10}/'],
            'pagina_web' => ['nullable','url'],
            'facebook' => ['nullable', 'regex:/^[A-Za-z0-9-_\.]{3,20}/'],
            'twitter' => ['nullable', 'regex:/^[A-Za-z0-9-_\.]{3,20}/'],
            'instagram' => ['nullable', 'regex:/^[A-Za-z0-9-_\.]{3,20}/'],
            'r_legal' => ['required', 'regex:/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/'],
            'doc_r_legal' => ['required', 'mimetypes:application/pdf', 'max:2048'],
            'cta_bancaria' => ['required','regex:/^[0-9]{5,30}/'],
            'doc_cta_bancaria' => ['required', 'mimetypes:application/pdf', 'max:2048'],
            'imss' => ['required', 'regex:/^[\d]{11}$/'],
            'doc_imss' => ['required', 'mimetypes:application/pdf', 'max:2048'],
            'ace_stps' => ['required', 'regex:/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/'],
            'doc_ace_stps' => ['required', 'mimetypes:application/pdf', 'max:2048'],
        ],
        [
            'razon_social.required' => 'Especifique la razón social o nombre',
            'razon_social.unique' => 'Esta razón social ya se encuentra registrada',
            'razon_social.max' => "Campo 'Razón social' no puede superar los 200 caracteres",
            'rfc.required' => 'Especifique el RFC',
            'rfc.unique' => 'Este RFC ya se encuentra registrado',
            'rfc.regex' => 'Formato de RFC no reconocido, intente de nuevo',
            'doc_rfc.required' => 'Seleccione archivo RFC (PDF)',
            'doc_rfc.mimetypes' => 'Archivo RFC debe ser tipo PDF',
            'doc_rfc.max' => 'Archivo RFC no debe superar los 2MB',
            'email.required' => 'Hace falta ingresar un email',
            'email.email' => 'Formato de email incorrecto/desconocido',
            'email.unique' => 'Este email ya se encuentra registrado',
            'telefono.required' => 'Ingrese número de teléfono',
            'telefono.regex' => 'Formato de teléfono desconocido, ingrese 10 digitos',
            'celular.regex' => 'Formato de celular desconocido, ingrese 10 digitos',
            'pagina_web.url' => 'Formato de URL (dirección web) no reconocido',
            'facebook.regex' => 'Formato de usuario facebook incorrecto, ejemplo: www.facebook.com/ingresaEsteUsuario',
            'twitter.regex' => 'Formato de usuario Twitter incorrecto, ejemplo: www.twitter.com/ingresaEsteUsuario',
            'instagram.regex' => 'Formato de usuario Instagram incorrecto, ejemplo: www.instagram.com/ingresaEsteUsuario',
            'r_legal.required' => 'Debe ingresar el nombre del representante legal',
            'r_legal.regex' => 'Formato de nombre del representante legal incorrecto/desconocido',
            'doc_r_legal.required' => 'Seleccione archivo R. Legal (PDF)',
            'doc_r_legal.mimetypes' => 'Archivo R. Legal debe ser tipo PDF',
            'doc_r_legal.max' => 'Archivo R. Legal no debe superar los 2MB',
            'cta_bancaria.required' => 'Debe ingresar el número de cuenta bancaria',
            'cta_bancaria.regex' => 'Formato de cuenta bancaria incorrecto/desconocido (sólo digitos sin espacios)',
            'doc_cta_bancaria.required' => 'Seleccione archivo Cta. bancaria (PDF)',
            'doc_cta_bancaria.mimetypes' => 'Archivo Cta. bancaria debe ser tipo PDF',
            'doc_cta_bancaria.max' => 'Archivo Cta. bancaria no debe superar los 2MB',
            'imss.required' => 'Debe ingresar el número de seguro social (IMSS)',
            'imss.regex' => 'Formato de seguro social (IMSS) incorrecto/desconocido (11 digitos)',
            'doc_imss.required' => 'Seleccione archivo IMSS (PDF)',
            'doc_imss.mimetypes' => 'Archivo IMSS debe ser tipo PDF',
            'doc_imss.max' => 'Archivo IMSS no debe superar los 2MB',
            'ace_stps.required' => 'Debe ingresar el nombre del agente capacitador externo',
            'ace_stps.regex' => 'Formato de nombre del agente capacitador externo incorrecto/desconocido',
            'doc_ace_stps.required' => 'Seleccione archivo ACE (PDF)',
            'doc_ace_stps.mimetypes' => 'Archivo ACE debe ser tipo PDF',
            'doc_ace_stps.max' => 'Archivo ACE no debe superar los 2MB',
        ]);

        //Persona Moral Lucrativa
        if(($request->tipo_persona === "Moral")&&($request->tipo_org === "Si")){    
            $validation = $request->validate([
                'doc_acta_constitutiva_lucrativa' => ['required', 'mimetypes:application/pdf', 'max:2048'],
                'doc_folio_reg_electronico_lucrativa' => ['required', 'mimetypes:application/pdf', 'max:2048']
            ],
            [
                'doc_acta_constitutiva_lucrativa.required' => 'Seleccione archivo Acta (PDF)',
                'doc_acta_constitutiva_lucrativa.mimetypes' => 'Archivo Acta debe ser tipo PDF',
                'doc_acta_constitutiva_lucrativa.max' => 'Archivo Acta no debe superar los 2MB',
                'doc_folio_reg_electronico_lucrativa.required' => 'Seleccione archivo Folio (PDF)',
                'doc_folio_reg_electronico_lucrativa.mimetypes' => 'Archivo Folio debe ser tipo PDF',
                'doc_folio_reg_electronico_lucrativa.max' => 'Archivo Folio no debe superar los 2MB',
            ]);
        }else if(($request->tipo_persona === "Moral")&&($request->tipo_org === "No")){ //No Lucrativa
            $validation = $request->validate([
                'doc_acta_constitutiva_no_lucrativa' => ['required', 'mimetypes:application/pdf', 'max:2048'],
                'doc_folio_reg_electronico_no_lucrativa' => ['required', 'mimetypes:application/pdf', 'max:2048'],
                'doc_autorizacion_fiscal' => ['required', 'mimetypes:application/pdf', 'max:2048'],
                'doc_reg_marca' => ['required', 'mimetypes:application/pdf', 'max:2048'],
                'doc_cluni' => ['required', 'mimetypes:application/pdf', 'max:2048'],
                'doc_multilaterales' => ['required', 'mimetypes:application/pdf', 'max:2048'],
            ],
            [
                'doc_acta_constitutiva_no_lucrativa.required' => 'Seleccione archivo Acta (PDF)',
                'doc_acta_constitutiva_no_lucrativa.mimetypes' => 'Archivo Acta debe ser tipo PDF',
                'doc_acta_constitutiva_no_lucrativa.max' => 'Archivo Acta no debe superar los 2MB',

                'doc_folio_reg_electronico_no_lucrativa.required' => 'Seleccione archivo Folio (PDF)',
                'doc_folio_reg_electronico_no_lucrativa.mimetypes' => 'Archivo Folio debe ser tipo PDF',
                'doc_folio_reg_electronico_no_lucrativa.max' => 'Archivo Folio no debe superar los 2MB',
                
                'doc_autorizacion_fiscal.required' => 'Seleccione archivo ADF (PDF)',
                'doc_autorizacion_fiscal.mimetypes' => 'Archivo ADF debe ser tipo PDF',
                'doc_autorizacion_fiscal.max' => 'Archivo ADF no debe superar los 2MB',
                
                'doc_reg_marca.required' => 'Seleccione archivo Marca (PDF)',
                'doc_reg_marca.mimetypes' => 'Archivo Marca debe ser tipo PDF',
                'doc_reg_marca.max' => 'Archivo Marca no debe superar los 2MB',
                
                'doc_cluni.required' => 'Seleccione archivo Cluni (PDF)',
                'doc_cluni.mimetypes' => 'Archivo Cluni debe ser tipo PDF',
                'doc_cluni.max' => 'Archivo Cluni no debe superar los 2MB',
                
                'doc_multilaterales.required' => 'Seleccione archivo Multilaterales (PDF)',
                'doc_multilaterales.mimetypes' => 'Archivo Multilaterales debe ser tipo PDF',
                'doc_multilaterales.max' => 'Archivo Multilaterales no debe superar los 2MB',
            ]);
        }

        //Saving docs
        //In Folder
        Storage::disk('local')->makeDirectory('public/clients/'.$request->razon_social);
        
        Storage::copy('public/clients/doc-no-found.pdf','public/clients/'.$request->razon_social.'/doc-no-found.pdf');

        $rfc = $request->doc_rfc->getClientOriginalName();
        $request->file('doc_rfc')->storeAs('public/clients/'.$request->razon_social,$rfc);
        
        $r_legal = $request->doc_r_legal->getClientOriginalName();
        $request->file('doc_r_legal')->storeAs('public/clients/'.$request->razon_social,$r_legal);

        $cta_bancaria = $request->doc_cta_bancaria->getClientOriginalName();
        $request->file('doc_cta_bancaria')->storeAs('public/clients/'.$request->razon_social,$cta_bancaria);
        
        $imss = $request->doc_imss->getClientOriginalName();
        $request->file('doc_imss')->storeAs('public/clients/'.$request->razon_social,$imss);
        
        $ace_stps = $request->doc_ace_stps->getClientOriginalName();
        $request->file('doc_ace_stps')->storeAs('public/clients/'.$request->razon_social,$ace_stps);

        
        //Persona Moral Lucrativa
        if(($request->tipo_persona === "Moral")&&($request->tipo_org === "Si")){ 
            $acta_constitutiva_lucrativa = $request->doc_acta_constitutiva_lucrativa->getClientOriginalName();
            $request->file('doc_acta_constitutiva_lucrativa')->storeAs('public/clients/'.$request->razon_social,$acta_constitutiva_lucrativa);
            
            $folio_reg_electronico_lucrativa = $request->doc_folio_reg_electronico_lucrativa->getClientOriginalName();
            $request->file('doc_folio_reg_electronico_lucrativa')->storeAs('public/clients/'.$request->razon_social,$folio_reg_electronico_lucrativa);

        }else if(($request->tipo_persona === "Moral")&&($request->tipo_org === "No")){ //No Lucrativa
            $acta_constitutiva_no_lucrativa = $request->doc_acta_constitutiva_no_lucrativa->getClientOriginalName();
            $request->file('doc_acta_constitutiva_no_lucrativa')->storeAs('public/clients/'.$request->razon_social,$acta_constitutiva_no_lucrativa);
            
            $folio_reg_electronico_no_lucrativa = $request->doc_folio_reg_electronico_no_lucrativa->getClientOriginalName();
            $request->file('doc_folio_reg_electronico_no_lucrativa')->storeAs('public/clients/'.$request->razon_social,$folio_reg_electronico_no_lucrativa);
            
            $autorizacion_fiscal = $request->doc_autorizacion_fiscal->getClientOriginalName();
            $request->file('doc_autorizacion_fiscal')->storeAs('public/clients/'.$request->razon_social,$autorizacion_fiscal);
            
            $reg_marca = $request->doc_reg_marca->getClientOriginalName();
            $request->file('doc_reg_marca')->storeAs('public/clients/'.$request->razon_social,$reg_marca);
            
            $cluni = $request->doc_cluni->getClientOriginalName();
            $request->file('doc_cluni')->storeAs('public/clients/'.$request->razon_social,$cluni);
            
            $multilaterales = $request->doc_multilaterales->getClientOriginalName();
            $request->file('doc_multilaterales')->storeAs('public/clients/'.$request->razon_social,$multilaterales);
        }

        //Saving data
        $lucrative = $request->tipo_org;
        if($request->tipo_persona === "Fisica"){
            $lucrative = null;
        }
        $newClient = new Client([
            'razon_social' => $request->razon_social,
            'tipo_persona' => $request->tipo_persona,
            'rfc' => $request->rfc,
            'email' => $request->email,
            'contraseña' => bcrypt('capax7'),
            'telefono' => $request->telefono,
            'celular' => $request->celular,
            'pagina_web' => $request->pagina_web,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'es_lucrativa' => $lucrative,
            'r_legal' => $request->r_legal,
            'cta_bancaria' => $request->cta_bancaria,
            'imss' => $request->imss,
            'ace_stps' => $request->ace_stps,
        ]);
        $newClient->save();

        //Retrieving Client ID
        $client = DB::table('clients')->where('email', $request->email)->select('id')->first();

        //Saving docs 
        //In DB
        $newDoc = new Document([
            'tipo' => 'rfc',
            'nombre' => $rfc,
            'cliente' => $client->id
        ]);
        $newDoc->save();
        $newDoc = new Document([
            'tipo' => 'r_legal',
            'nombre' => $request->doc_r_legal->getClientOriginalName(),
            'cliente' => $client->id
        ]);
        $newDoc->save();
        $newDoc = new Document([
            'tipo' => 'cta_bancaria',
            'nombre' => $request->doc_cta_bancaria->getClientOriginalName(),
            'cliente' => $client->id
        ]);
        $newDoc->save();
        $newDoc = new Document([
            'tipo' => 'imss',
            'nombre' => $request->doc_imss->getClientOriginalName(),
            'cliente' => $client->id
        ]);
        $newDoc->save();
        $newDoc = new Document([
            'tipo' => 'ace_stps',
            'nombre' => $request->doc_ace_stps->getClientOriginalName(),
            'cliente' => $client->id
        ]);
        $newDoc->save();
        
        //Persona Moral Lucrativa
        if(($request->tipo_persona === "Moral")&&($request->tipo_org === "Si")){ 
            $newDoc = new Document([
                'tipo' => 'acta_constitutiva',
                'nombre' => $request->doc_acta_constitutiva_lucrativa->getClientOriginalName(),
                'cliente' => $client->id
            ]);
            $newDoc->save();
            $newDoc = new Document([
                'tipo' => 'folio_reg_electronico',
                'nombre' => $request->doc_folio_reg_electronico_lucrativa->getClientOriginalName(),
                'cliente' => $client->id
            ]);
            $newDoc->save();
        }else if(($request->tipo_persona === "Moral")&&($request->tipo_org === "No")){ //No Lucrativa
            $newDoc = new Document([
                'tipo' => 'acta_constitutiva',
                'nombre' => $request->doc_acta_constitutiva_no_lucrativa->getClientOriginalName(),
                'cliente' => $client->id
            ]);
            $newDoc->save();
            $newDoc = new Document([
                'tipo' => 'folio_reg_electronico',
                'nombre' => $request->doc_folio_reg_electronico_no_lucrativa->getClientOriginalName(),
                'cliente' => $client->id
            ]);
            $newDoc->save();
            $newDoc = new Document([
                'tipo' => 'autorizacion_fiscal',
                'nombre' => $request->doc_autorizacion_fiscal->getClientOriginalName(),
                'cliente' => $client->id
            ]);
            $newDoc->save();
            $newDoc = new Document([
                'tipo' => 'reg_marca',
                'nombre' => $request->doc_reg_marca->getClientOriginalName(),
                'cliente' => $client->id
            ]);
            $newDoc->save();
            $newDoc = new Document([
                'tipo' => 'cluni',
                'nombre' => $request->doc_cluni->getClientOriginalName(),
                'cliente' => $client->id
            ]);
            $newDoc->save();
            $newDoc = new Document([
                'tipo' => 'multilaterales',
                'nombre' => $request->doc_multilaterales->getClientOriginalName(),
                'cliente' => $client->id
            ]);
            $newDoc->save();
        }
        
        //Returning to showRegisterClient
        return redirect()->route('showRegisterClient')->with('success','Registro éxitoso');
    }
}
