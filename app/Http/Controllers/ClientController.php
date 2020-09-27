<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\Donor;

class ClientController extends Controller
{
    //Views
    public function showDonorsMenu(){
        if(auth('client')->check()){
            return view('Client.DonorsMenu');
        }else{
            return redirect()->route('main');
        }
    }
    public function showRegisterDonor(){
        if(auth('client')->check()){
            return view('Client.RegisterDonor');
        }else{
            return redirect()->route('main');
        }
    }
    public function showDonors(){
        $donors = DB::table('donors')->where('registrado_por', auth('client')->user()->id)->select('*')->get();
        if(auth('client')->check()){            
            return view('Client.Donors')->with('donors',$donors);
        }else{
            return redirect()->route('main');
        }
    }    
    public function showRegisterDonation(){
        if(auth('client')->check()){
            return view('Client.RegisterDonation');
        }else{
            return redirect()->route('main');
        }
    }
    public function showDonations(){
        if(auth('client')->check()){
            return view('Client.Donations');
        }else{
            return redirect()->route('main');
        }
    }

    //Functions
    public function updateDocument(Request $request){
        $currentDoc = 'doc_'.$request->doc;
        $validator = Validator::make($request->all(), [
            $currentDoc => ['required','mimetypes:application/pdf', 'max:2000'],
        ],
        [
            $currentDoc.'.required' => 'Seleccione un archivo válido (PDF)',
            $currentDoc.'.mimetypes' => 'El archivo debe ser tipo PDF',
            $currentDoc.'.max' => 'El archivo no debe superar los 2MB',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }else{
            //Comparing names
            $files = DB::table('documents')->where('cliente', auth('client')->user()->id)->select('nombre')->get();
            //Compare names
            $sameName = false;
            foreach($files as $file){
                if($request->$currentDoc->getClientOriginalName() == $file->nombre){
                    $sameName = true;
                }
            }
            if(!$sameName){
                //Special cases
                $nameDoc = $request->doc;
                if(($nameDoc === "acta_constitutiva_lucrativa") || ($nameDoc === "acta_constitutiva_no_lucrativa")){
                    $nameDoc = "acta_constitutiva";
                }
                if(($nameDoc === "folio_reg_electronico_lucrativa") || ($nameDoc === "folio_reg_electronico_no_lucrativa")){
                    $nameDoc = "folio_reg_electronico";
                }
                //Verify if there's a file already in system
                $file_exists = DB::table('documents')->where('cliente', auth('client')->user()->id)->where('tipo', $nameDoc)->select('nombre')->get();
                if(!$file_exists->isEmpty()){                
                    //Save new file
                    $newDoc = $request->$currentDoc->getClientOriginalName();
                    $request->file($currentDoc)->storeAs('public/clients/'.auth('client')->user()->razon_social,$newDoc);
                    //Update file name
                    $updatingFileName = DB::table('documents')->where('cliente', auth('client')->user()->id)->where('tipo', $nameDoc)->update(['nombre'=>($request->$currentDoc->getClientOriginalName())]);
                    //Delete old file
                    Storage::delete('/public/clients'.'/'.auth('client')->user()->razon_social.'/'.$file_exists->get(0)->nombre);
                    return back()->with('success','Archivo actualizado correctamente');
                }else{
                    //If file doesn't exist it means the orgType changed
                    $currentTypeOrg = DB::table('clients')->where('id', auth('client')->user()->id)->select('tipo_persona')->first();
                    if($request->orgType == $currentTypeOrg->tipo_persona){
                        //Special cases
                        $isLucrative = DB::table('clients')->where('id', auth('client')->user()->id)->select('es_lucrativa')->first();
                        if($isLucrative->es_lucrativa == "Si"){
                            if(($nameDoc === "acta_constitutiva") || ($nameDoc === "folio_reg_electronico")){
                                //Save new file
                                $newDoc = $request->$currentDoc->getClientOriginalName();
                                $request->file($currentDoc)->storeAs('public/clients/'.auth('client')->user()->razon_social,$newDoc);
                                //Update file row
                                $newDoc = new Document([
                                    'tipo' => $nameDoc,
                                    'nombre' => $newDoc,
                                    'cliente' => auth('client')->user()->id
                                ]);
                                $newDoc->save();
                                return back()->with('success','Archivo actualizado correctamente');
                            }else{
                                return back()->withErrors(['Para subir este documento modifique su "Tipo" a "No Lucrativa"']);
                            }
                        }else{
                            //Save new file
                            $newDoc = $request->$currentDoc->getClientOriginalName();
                            $request->file($currentDoc)->storeAs('public/clients/'.auth('client')->user()->razon_social,$newDoc);
                            //Update file name
                            $updatingFileName = DB::table('documents')->where('cliente', auth('client')->user()->id)->where('tipo', $nameDoc)->update(['nombre'=>($request->$currentDoc->getClientOriginalName())]);
                            //Delete old file
                            Storage::delete('/public/clients'.'/'.auth('client')->user()->razon_social.'/'.$file_exists->get(0)->nombre);
                            return back()->with('success','Archivo actualizado correctamente');
                        }
                    }else{
                        return back()->withErrors(['Para subir este documento modifique antes su "Persona" a "Moral"']);
                    }
                }
            }else{
                return back()->withErrors(['Cambie el nombre del nuevo archivo, no puede ser igual al actual']);
            }
        }
    }
    public function updateAdministrativeInformation(Request $request){
        $orgData = DB::table('clients')->where('id', auth('client')->user()->id)->select('*')->first();
        //Persona changed
        if($request->tipo_persona != $orgData->tipo_persona){
            //From Moral to Fisica
            if($orgData->tipo_persona === "Moral"){   
                $files = DB::table('documents')->where('cliente', auth('client')->user()->id)->select('tipo','nombre')->get();
                foreach($files as $file){
                    //Deleting extra files
                    if(($file->tipo == "folio_reg_electronico") || ($file->tipo == "acta_constitutiva")){
                        Storage::delete('/public/clients'.'/'.auth('client')->user()->razon_social.'/'.$file->nombre);             
                        $deletingReferences = DB::table('documents')->where('cliente', auth('client')->user()->id)->where('tipo', $file->tipo)->delete();
                    }
                }
                //Was non-lucrative
                if($orgData->es_lucrativa === "No"){
                    foreach($files as $file){
                        //Deleting extra files
                        if(($file->tipo == "multilaterales") || ($file->tipo == "cluni") || ($file->tipo == "reg_marca") || ($file->tipo == "autorizacion_fiscal")){
                            Storage::delete('/public/clients'.'/'.auth('client')->user()->razon_social.'/'.$file->nombre);
                            $deletingReferences = DB::table('documents')->where('cliente', auth('client')->user()->id)->where('tipo', $file->tipo)->delete();
                        }
                    }
                }
                //Updating if it's lucrative
                $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['es_lucrativa'=>(null)]);
            }else{
                //Updating if it's lucrative
                $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['es_lucrativa'=>($request->tipo_org)]);
            }
            //Updating org type
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['tipo_persona'=>($request->tipo_persona)]);
        }
        //Moral type changed
        if(($request->tipo_org != $orgData->es_lucrativa) && ($orgData->es_lucrativa != null)){
            //If was non lucrative
            if($orgData->es_lucrative == "No"){
                $files = DB::table('documents')->where('cliente', auth('client')->user()->id)->select('tipo','nombre')->get();
                //Deleting extra files
                foreach($files as $file){
                    //Deleting extra files
                    if(($file->tipo == "multilaterales") || ($file->tipo == "cluni") || ($file->tipo == "reg_marca") || ($file->tipo == "autorizacion_fiscal")){
                        Storage::delete('/public/clients'.'/'.auth('client')->user()->razon_social.'/'.$file->nombre);
                        $deletingReferences = DB::table('documents')->where('cliente', auth('client')->user()->id)->where('tipo', $file->tipo)->delete();
                    }
                }
            }
            //Changing lucrative or non lucrative
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['es_lucrativa'=>($request->tipo_org)]);
        }

        if($request->razon_social != $orgData->razon_social){
            $validation = $request->validate([
                'razon_social' => ['required','unique:clients', 'max:200'],
            ],[
                'razon_social.required' => 'Especifique la razón social o nombre',
                'razon_social.unique' => 'Esta razón social ya se encuentra registrada',
                'razon_social.max' => "Campo 'Razón social' no puede superar los 200 caracteres",
            ]);
            Storage::move('public/clients/'.$orgData->razon_social, 'public/clients/'.$request->razon_social); //rename folder
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['razon_social'=>($request->razon_social)]);
        }

        if($request->rfc != $orgData->rfc){
            $validation = $request->validate([
                'rfc' => ['required','unique:clients', 'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/'],
            ],[
                'rfc.required' => 'Especifique el RFC',
                'rfc.unique' => 'Este RFC ya se encuentra registrado',
                'rfc.regex' => 'Formato de RFC no reconocido, intente de nuevo',
            ]);
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['rfc'=>($request->rfc)]);
        }

        if($request->r_legal != $orgData->r_legal){
            $validation = $request->validate([
                'r_legal' => ['required', 'regex:/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/'],
            ],[
                'r_legal.required' => 'Debe ingresar el nombre del representante legal',
                'r_legal.regex' => 'Formato de nombre del representante legal incorrecto/desconocido',
            ]);
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['r_legal'=>($request->r_legal)]);
        }

        if($request->cta_bancaria != $orgData->cta_bancaria){
            $validation = $request->validate([
                'cta_bancaria' => ['required','regex:/^[0-9]{5,30}/'],
            ],[
                'cta_bancaria.required' => 'Debe ingresar el número de cuenta bancaria',
                'cta_bancaria.regex' => 'Formato de cuenta bancaria incorrecto/desconocido (sólo digitos sin espacios)',
            ]);
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['cta_bancaria'=>($request->cta_bancaria)]);
        }

        if($request->imss != $orgData->imss){
            $validation = $request->validate([
                'imss' => ['required', 'regex:/^[\d]{11}$/'],
            ],[
                'imss.required' => 'Debe ingresar el número de seguro social (IMSS)',
                'imss.regex' => 'Formato de seguro social (IMSS) incorrecto/desconocido (11 digitos)',
            ]);
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['imss'=>($request->imss)]);
        }

        if($request->ace_stps != $orgData->ace_stps){
            $validation = $request->validate([
                'ace_stps' => ['required', 'regex:/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/'],
            ],
            [
                'ace_stps.required' => 'Debe ingresar el nombre del agente capacitador externo',
                'ace_stps.regex' => 'Formato de nombre del agente capacitador externo incorrecto/desconocido',
            ]);
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['ace_stps'=>($request->ace_stps)]);
        }

        return back()->with('success', "Datos actualizados. Asegurate de actualizar tus documentos adecuadamente");
    }
    public function updateGeneralInformation(Request $request){
        $orgData = DB::table('clients')->where('id', auth('client')->user()->id)->select('*')->first(); 

        if($request->email != $orgData->email){      
            $validation = $request->validate([
                'email' => ['required','email','unique:clients'], 
            ],
            [
                'email.required' => 'Hace falta ingresar un email',
                'email.email' => 'Formato de email incorrecto/desconocido',
                'email.unique' => 'Este email ya se encuentra registrado',
            ]);   
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['email'=>($request->email)]);
        }

        if(($request->contraseña != $orgData->contraseña) && ($request->contraseña != '******')){ 
            //Validation
            $validation = $request->validate([
                'contraseña' => ['required', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\ ^&\*]).{6,}$/'],
            ],
            [
                'contraseña.required' => 'Hace falta ingresar una contraseña',
                'contraseña.regex' => 'La contraseña debe ser de al menos 6 caracteres e incluir al menos un número, un caracter especial y una letra mayúscula',
            ]);
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['contraseña'=>bcrypt($request->contraseña)]);
        }

        //Validation
        $validation = $request->validate([
            'telefono' => ['required','regex:/^[0-9]{10}/'],
            'celular' => ['nullable','regex:/^[0-9]{10}/'],
            'pagina_web' => ['nullable','url'],
            'facebook' => ['nullable', 'regex:/^[A-Za-z0-9-_\.]{3,20}/'],
            'twitter' => ['nullable', 'regex:/^[A-Za-z0-9-_\.]{3,20}/'],
            'instagram' => ['nullable', 'regex:/^[A-Za-z0-9-_\.]{3,20}/'],
        ],
        [
            'telefono.required' => 'Ingrese número de teléfono',
            'telefono.regex' => 'Formato de teléfono desconocido, ingrese 10 digitos',
            'celular.regex' => 'Formato de celular desconocido, ingrese 10 digitos',
            'pagina_web.url' => 'Formato de URL (dirección web) no reconocido',
            'facebook.regex' => 'Formato de usuario facebook incorrecto, ejemplo: www.facebook.com/ingresaEsteUsuario',
            'twitter.regex' => 'Formato de usuario Twitter incorrecto, ejemplo: www.twitter.com/ingresaEsteUsuario',
            'instagram.regex' => 'Formato de usuario Instagram incorrecto, ejemplo: www.instagram.com/ingresaEsteUsuario',
        ]);

        if($request->telefono != $orgData->telefono){   
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['telefono'=>($request->telefono)]);
        }

        if($request->celular != $orgData->celular){   
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['celular'=>($request->celular)]);
        }

        if($request->pagina_web != $orgData->pagina_web){   
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['pagina_web'=>($request->pagina_web)]);
        }

        if($request->facebook != $orgData->facebook){
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['facebook'=>($request->facebook)]);
        }

        if($request->twitter != $orgData->twitter){   
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['twitter'=>($request->twitter)]);
        }

        if($request->instagram != $orgData->instagram){   
            $updating = DB::table('clients')->where('id', auth('client')->user()->id)->update(['instagram'=>($request->instagram)]);
        }

        return back()->with('success','Datos actualizados correctamente');
    }

    public function registerDonor(Request $request){
        //Validation
        $validation = $request->validate([
            'razon_social' => ['required','unique:clients', 'max:200'],
            'rfc' => ['required','unique:donors', 'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/'],
            'nacionalidad' => ['required','regex:/^[A-Za-zÁÉÍÓÚáéíóú]{4,100}/'],
            'email' => ['required','email','unique:donors'],
            'telefono' => ['required','regex:/^[0-9]{10}/'],
            'celular' => ['nullable','regex:/^[0-9]{10}/'],
            'domicilio' => ['required','regex:/^(Calle)\s[\w\s\.]{1,30}(\s\#\d{0,3}\s){0,1}(Colonia)\s[\wÁÉÍÓÚáéíóú\s\.]{1,30}(C)\.(P)\.\s[\d]{5}$/'],
        ],
        [
            'razon_social.required' => 'Especifique la razón social o nombre',
            'razon_social.unique' => 'Esta razón social ya se encuentra registrada',
            'razon_social.max' => "Campo 'Razón social' no puede superar los 200 caracteres",
            'rfc.required' => 'Especifique el RFC',
            'rfc.unique' => 'Este RFC ya se encuentra registrado',
            'rfc.regex' => 'Formato de RFC no reconocido, intente de nuevo',
            'email.required' => 'Hace falta ingresar un email',
            'email.email' => 'Formato de email incorrecto/desconocido',
            'email.unique' => 'Este email ya se encuentra registrado',
            'nacionalidad.required' => 'Campo "Nacionalidad" requerido',
            'nacionalidad.regex' => 'Nacionalidad no reconocida. Use sólo de 4 a 100 caracteres',
            'telefono.required' => 'Ingrese número de teléfono',
            'telefono.regex' => 'Formato de teléfono desconocido, ingrese 10 digitos',
            'celular.regex' => 'Formato de celular desconocido, ingrese 10 digitos',
            'domicilio.required' => 'Especifique la dirección',
            'domicilio.regex' => 'Formato de dirección no válido. Ejemplo "Calle Independencia #3 Colonia Centro de la colonia C.P. 12345"',
        ]);
        $newDonor = new Donor([
            'razon_social' => $request->razon_social,
            'tipo_persona' => $request->tipo_persona,
            'rfc' => $request->rfc,
            'nacionalidad' => $request->nacionalidad,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'celular' => $request->celular,
            'domicilio' => $request->domicilio,
            'registrado_por' => auth('client')->user()->id,
        ]);
        $newDonor->save();
        return back()->with('success',"Donante registrado con éxito");
    }
    public function updateDonors(Request $request){
        
    }
}
