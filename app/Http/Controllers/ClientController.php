<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class ClientController extends Controller
{
    //NavBar menu views
    public function showRegisterDonor(){
        if(auth('client')->check()){
            return view('Client.RegisterDonor');
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
            
        }
        /*Validation
        $validation = $request->validate([
            'razon_social' => ['required','unique:clients', 'max:200'],
            'rfc' => ['required','unique:clients', 'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/'],
            'r_legal' => ['required', 'regex:/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/'],
            'doc_r_legal' => ['required', 'mimetypes:application/pdf', 'max:2048'],
            'cta_bancaria' => ['required','regex:/^[0-9]{5,30}/'],
            'imss' => ['required', 'regex:/^[\d]{11}$/'],
            'ace_stps' => ['required', 'regex:/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/'],
        ],
        [
            'razon_social.required' => 'Especifique la razón social o nombre',
            'razon_social.unique' => 'Esta razón social ya se encuentra registrada',
            'razon_social.max' => "Campo 'Razón social' no puede superar los 200 caracteres",
            'rfc.required' => 'Especifique el RFC',
            'rfc.unique' => 'Este RFC ya se encuentra registrado',
            'rfc.regex' => 'Formato de RFC no reconocido, intente de nuevo',
            'r_legal.required' => 'Debe ingresar el nombre del representante legal',
            'r_legal.regex' => 'Formato de nombre del representante legal incorrecto/desconocido',
            'cta_bancaria.required' => 'Debe ingresar el número de cuenta bancaria',
            'cta_bancaria.regex' => 'Formato de cuenta bancaria incorrecto/desconocido (sólo digitos sin espacios)',
            'imss.required' => 'Debe ingresar el número de seguro social (IMSS)',
            'imss.regex' => 'Formato de seguro social (IMSS) incorrecto/desconocido (11 digitos)',
            'ace_stps.required' => 'Debe ingresar el nombre del agente capacitador externo',
            'ace_stps.regex' => 'Formato de nombre del agente capacitador externo incorrecto/desconocido',
        ]);*/
        
    }
}
