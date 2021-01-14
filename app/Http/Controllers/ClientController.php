<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\Donor;
use Illuminate\Support\Carbon;
use App\Models\Donation;
use App\Models\Revenue;
use App\Models\Expense;


class ClientController extends Controller
{
    //This fix an laravel error with PHP 8
    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
    //Views
    public function showDonorsMenu(){
        if(auth('client')->check()){
            return view('Client.DonorsMenu');
        }else{
            return redirect()->route('main');
        }
    }
    public function showDataMenu(){
        if(auth('client')->check()){
            return view('Client.DataMenu');
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
        if(auth('client')->check()){     
            $donors = DB::table('donors')->where('registrado_por', auth('client')->user()->id)->select('*')->get();       
            return view('Client.Donors')->with('donors',$donors);
        }else{
            return redirect()->route('main');
        }
    }    
    public function showRegisterDonation(){
        if(auth('client')->check()){
            $donors = DB::table('donors')->where('registrado_por', auth('client')->user()->id)->select('id','razon_social')->get();
            return view('Client.RegisterDonation')->with('donors',$donors)->with('date', Carbon::now()->locale('es')->isoFormat('DD MMMM YYYY'));
        }else{
            return redirect()->route('main');
        }
    }
    public function showDonations(){
        if(auth('client')->check()){
            $donors = DB::table('donors')->where('registrado_por', auth('client')->user()->id)->select('*')->get();
            return view('Client.Donations')->with('donors',$donors);
        }else{
            return redirect()->route('main');
        }
    }
    public function showStatistics(){
        if(auth('client')->check()){
            $donors = DB::table('donors')->where('registrado_por', auth('client')->user()->id)->select('*')->get();       
            $currentDate = Carbon::now();
            $year = $currentDate->format('Y');
            $months = $currentDate->format('m');
            $bestDonorID = DB::select(DB::raw('select sum(cantidad) as total, donante from donations where fecha >= "'.$year.'-01-01" and fecha <= "'.$year.'-12-31" and cliente = "'.auth('client')->user()->id.'" group by donante order by total desc limit 1'));
            $bestDonor = DB::table('donors')->where('registrado_por', auth('client')->user()->id)->where('id',$bestDonorID[0]->donante ?? '')->select('razon_social')->first();
            $last5Donations = DB::table('donations')->where('cliente', auth('client')->user()->id)->select('*')->orderBy('fecha','desc')->limit(5)->get();
            $anualIncome = DB::table('donations')->where('cliente', auth('client')->user()->id)->whereYear('fecha',$year)->sum('cantidad');
            return view('Client.Statistics')->with('last5Donations',$last5Donations)
                    ->with('donors',$donors)->with('bestDonor',$bestDonor->razon_social ?? 'Sin donaciones')
                    ->with('monthAvg',($bestDonorID[0]->total ?? 0)/$months)
                    ->with('anualIncome',$anualIncome)
                    ->with('anualMonthAvg',($anualIncome/$months));
        }else{
            return redirect()->route('main');
        }
    }
    public function showDetailedDonors(){
        if(auth('client')->check()){
            $currentDate = Carbon::now();
            $year = $currentDate->format('Y');
            $month = $currentDate->format('m');
            $registrado_en = DB::table('clients')->where('id', auth('client')->user()->id)->select('registrado_en')->get();
            $donaciones = DB::select(DB::raw('select fecha, donante, razon_social, sum(cantidad) as total from donations inner join donors on donante = donors.id where cliente = '.auth('client')->user()->id.' and fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" group by donante order by total desc limit 5'));;
            return view('Client.StatisticsDonors')->with('year', $registrado_en[0]->registrado_en)->with('donaciones', $donaciones)->with('currentYear',$year)->with('currentMonth',$month)->with('selectedYear', "")->with('selectedMonth', "");;
            /*
            $historicDonors = DB::select(DB::raw('select fecha, donante, razon_social, sum(cantidad) as total from donations inner join donors on donante = donors.id where cliente = '.auth('client')->user()->id.' group by donante order by total desc limit 5'));
            $anualDonors = DB::select(DB::raw('select fecha, donante, razon_social, sum(cantidad) as total from donations inner join donors on donante = donors.id where cliente = '.auth('client')->user()->id.' and fecha >= "'.$year.'-01-01" group by donante order by total desc limit 5'));
            $montDonors = DB::select(DB::raw('select fecha, donante, razon_social, sum(cantidad) as total from donations inner join donors on donante = donors.id where cliente = '.auth('client')->user()->id.' and fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" group by donante order by total desc limit 5'));
            return view('Client.StatisticsDonors')->with('historicDonors', $historicDonors)->with('anualDonors', $anualDonors)->with('monthDonors', $montDonors);
            */
        }else{
            return redirect()->route('main');
        }
    }
    public function showDetailedDonorsMonthYear(Request $request){
        if(auth('client')->check()){
            $year = $request->year;
            $month = $request->month;
            $currentDate = Carbon::now();
            $currentYear = $currentDate->format('Y');
            $currentMonth = $currentDate->format('m');
            $registrado_en = DB::table('clients')->where('id', auth('client')->user()->id)->select('registrado_en')->get();
            $montDonors = DB::select(DB::raw('select fecha, donante, razon_social, sum(cantidad) as total from donations inner join donors on donante = donors.id where cliente = '.auth('client')->user()->id.' and fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" group by donante order by total desc limit 5'));
            return view('Client.StatisticsDonors')->with('donaciones', $montDonors)->with('year', $registrado_en[0]->registrado_en)->with('currentYear',$currentYear)->with('currentMonth',$currentMonth)->with('selectedYear', $year)->with('selectedMonth', $month);
            
        }else{
            return redirect()->route('main');
        }
    }
    public function showDetailedDonations(){
        if(auth('client')->check()){
            $currentDate = Carbon::now();
            $year = $currentDate->format('Y');
            $month = $currentDate->format('m');
            $registrado_en = DB::table('clients')->where('id', auth('client')->user()->id)->select('registrado_en')->get();
            $donaciones = DB::select(DB::raw('select sum(cantidad) as total from donations where fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" and cliente = '.auth('client')->user()->id));
            return view('Client.StatisticsDonations')->with('donaciones', $donaciones)->with('añoActual', $year)->with('añoRegistro',  $registrado_en[0]->registrado_en)->with('añoSeleccionado', $year)->with('mesSeleccionado',$month);

            /*
            $thisYearDonations = DB::select(DB::raw('select sum(cantidad) as total from donations where fecha >= "'.$year.'-01-01" and fecha <= "'.$year.'-12-31" and cliente = '.auth('client')->user()->id));
            $lastYearDonations = DB::select(DB::raw('select sum(cantidad) as total from donations where fecha >= "'.($year-1).'-01-01" and fecha <= "'.($year-1).'-12-31" and cliente = '.auth('client')->user()->id));
            $lastLastYearDonations = DB::select(DB::raw('select sum(cantidad) as total from donations where fecha >= "'.($year-2).'-01-01" and fecha <= "'.($year-2).'-12-31" and cliente = '.auth('client')->user()->id));
            $thisMonthDonations = DB::select(DB::raw('select sum(cantidad) as total from donations where fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" and cliente = '.auth('client')->user()->id));
            $lastMonth = $month; $lastLastMonth = $month;
            $lastYear = $year; $lastLastYear = $year;
            if($month == "02"){
                $lastMonth = $month-1;
                $lastLastMonth = "12";
                $lastLastYear = $year-1;
            }else if($month == "01"){
                $lastMonth = "12";
                $lastYear = $year-1;
                $lastLastMonth = "11";
                $lastLastYear = $year-1;
            }else{
                $lastMonth = $month-1;
                $lastLastMonth = $month-2;
            }
            $lastMonthDonations = DB::select(DB::raw('select sum(cantidad) as total from donations where fecha >= "'.$lastYear.'-'.$lastMonth.'-01" and fecha <= "'.$lastYear.'-'.$lastLastMonth.'-31" and cliente = '.auth('client')->user()->id));
            $lastLastMonthDonations = DB::select(DB::raw('select sum(cantidad) as total from donations where fecha >= "'.$lastLastYear.'-'.$lastLastMonth.'-01" and fecha <= "'.$lastLastYear.'-'.$lastLastMonth.'-31" and cliente = '.auth('client')->user()->id));

            return view('Client.StatisticsDonations')->with('thisYearDonations',$thisYearDonations[0]->total)->with('lastYearDonations',$lastYearDonations[0]->total)->with('lastLastYearDonations',$lastLastYearDonations[0]->total)
                                                    ->with('years',[($year-2), ($year-1), $year])
                                                    ->with('thisMonthDonations',$thisMonthDonations[0]->total)->with('lastMonthDonations',$lastMonthDonations[0]->total)->with('lastLastMonthDonations',$lastLastMonthDonations[0]->total)
                                                    ->with('months',[$lastLastMonth,$lastMonth,$month]);
                                                    */
        }else{
            return redirect()->route('main');
        }
    }
    public function showDetailedDonationsMonthYear(Request $request){
        if(auth('client')->check()){
            $currentDate = Carbon::now();
            $currentYear = $currentDate->format('Y');
            //$currentMonth = $currentDate->format('m');
            $year = $request->year;
            $month = $request->month;

            $registrado_en = DB::table('clients')->where('id', auth('client')->user()->id)->select('registrado_en')->get();
            $donaciones = DB::select(DB::raw('select sum(cantidad) as total from donations where fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" and cliente = '.auth('client')->user()->id));
            return view('Client.StatisticsDonations')->with('donaciones', $donaciones)->with('añoActual', $currentYear)->with('añoRegistro',  $registrado_en[0]->registrado_en)->with('añoSeleccionado', $year)->with('mesSeleccionado',$month);

        }else{
            return redirect()->route('main');
        }
    }
    public function showRegisterRevenue(){
        if(auth('client')->check()){
            return view('Client.RegisterRevenue')->with('date', Carbon::now()->locale('es')->isoFormat('DD MMMM YYYY'));
        }else{
            return redirect()->route('main');
        }
    }
    public function showRegisterExpense(){
        if(auth('client')->check()){
            return view('Client.RegisterExpense')->with('date', Carbon::now()->locale('es')->isoFormat('DD MMMM YYYY'));
        }else{
            return redirect()->route('main');
        }
    }
    public function showRevenues(){
        if(auth('client')->check()){
            $currentDate = Carbon::now();
            $year = $currentDate->format('Y');
            $month = $currentDate->format('m');
            $registrado_en = DB::table('clients')->where('id', auth('client')->user()->id)->select('registrado_en')->get();
            $ingresos = DB::select(DB::raw('select sum(cantidad) as total from revenues where fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" and cliente = '.auth('client')->user()->id));
            return view('Client.StatisticsRevenues')->with('ingresos', $ingresos)->with('añoActual', $year)->with('añoRegistro',  $registrado_en[0]->registrado_en)->with('añoSeleccionado', $year)->with('mesSeleccionado',$month);

        }else{
            return redirect()->route('main');
        }
    }
    public function showDetailedRevenuesMonthYear(Request $request){
        if(auth('client')->check()){
            $currentDate = Carbon::now();
            $currentYear = $currentDate->format('Y');
            //$currentMonth = $currentDate->format('m');
            $year = $request->year;
            $month = $request->month;

            $registrado_en = DB::table('clients')->where('id', auth('client')->user()->id)->select('registrado_en')->get();
            $donaciones = DB::select(DB::raw('select sum(cantidad) as total from revenues where fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" and cliente = '.auth('client')->user()->id));
            return view('Client.StatisticsRevenues')->with('ingresos', $donaciones)->with('añoActual', $currentYear)->with('añoRegistro',  $registrado_en[0]->registrado_en)->with('añoSeleccionado', $year)->with('mesSeleccionado',$month);

        }else{
            return redirect()->route('main');
        }
    }
    
    public function showExpenses(){
        if(auth('client')->check()){
            $currentDate = Carbon::now();
            $year = $currentDate->format('Y');
            $month = $currentDate->format('m');
            $registrado_en = DB::table('clients')->where('id', auth('client')->user()->id)->select('registrado_en')->get();
            $ingresos = DB::select(DB::raw('select sum(cantidad) as total from expenses where fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" and cliente = '.auth('client')->user()->id));
            return view('Client.StatisticsExpenses')->with('gastos', $ingresos)->with('añoActual', $year)->with('añoRegistro',  $registrado_en[0]->registrado_en)->with('añoSeleccionado', $year)->with('mesSeleccionado',$month);

        }else{
            return redirect()->route('main');
        }
    }
    public function showDetailedExpensesMonthYear(Request $request){
        if(auth('client')->check()){
            $currentDate = Carbon::now();
            $currentYear = $currentDate->format('Y');
            //$currentMonth = $currentDate->format('m');
            $year = $request->year;
            $month = $request->month;

            $registrado_en = DB::table('clients')->where('id', auth('client')->user()->id)->select('registrado_en')->get();
            $donaciones = DB::select(DB::raw('select sum(cantidad) as total from expenses where fecha >= "'.$year.'-'.$month.'-01" and fecha <= "'.$year.'-'.$month.'-31" and cliente = '.auth('client')->user()->id));
            return view('Client.StatisticsExpenses')->with('gastos', $donaciones)->with('añoActual', $currentYear)->with('añoRegistro',  $registrado_en[0]->registrado_en)->with('añoSeleccionado', $year)->with('mesSeleccionado',$month);

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
                if(!$file_exists->isEmpty() || $request->doc == 'rfc' || $request->doc == 'r_legal' || $request->doc == 'cta_bancaria' || $request->doc == 'imss' || $request->doc == 'ace_stps'){                
                    //Save new file
                    $newDoc = $request->$currentDoc->getClientOriginalName();
                    $request->file($currentDoc)->storeAs('public/clients/'.auth('client')->user()->razon_social,$newDoc);

                    if($request->doc == 'rfc' || $request->doc == 'r_legal' || $request->doc == 'cta_bancaria' || $request->doc == 'imss' || $request->doc == 'ace_stps'){
                        $newDoc = new Document([
                            'tipo' => $request->doc,
                            'nombre' => $newDoc,
                            'cliente' => auth('client')->user()->id
                        ]);
                        $newDoc->save();
                    }else{
                        //Update file name
                        $updatingFileName = DB::table('documents')->where('cliente', auth('client')->user()->id)->where('tipo', $nameDoc)->update(['nombre'=>($request->$currentDoc->getClientOriginalName())]);
                        //Delete old file
                        Storage::delete('/public/clients'.'/'.auth('client')->user()->razon_social.'/'.$file_exists->get(0)->nombre);
                    }
                    try {
                        \Mail::to(auth('client')->user()->email)->send(new \App\Mail\InformationChangesMade());
                    } catch (\Exception $e) {
                        return back()->withErrors($e->getMessage());
                    }
                    return back()->with('success','Archivo actualizado correctamente');
                }else{
                    //If file doesn't exist it means the orgType changed or that the file hasn't been added
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
                                try {
                                    \Mail::to(auth('client')->user()->email)->send(new \App\Mail\InformationChangesMade());
                                } catch (\Exception $e) {
                                    return back()->withErrors($e->getMessage());
                                }
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
                            try {
                                \Mail::to(auth('client')->user()->email)->send(new \App\Mail\InformationChangesMade());
                            } catch (\Exception $e) {
                                return back()->withErrors($e->getMessage());
                            }
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

        try {
            \Mail::to(auth('client')->user()->email)->send(new \App\Mail\InformationChangesMade());
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
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

        try {
            \Mail::to(auth('client')->user()->email)->send(new \App\Mail\InformationChangesMade());
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
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
            'celular' => ['nullable','regex:/^[0-9]{10}/'],
            'calle' => ['required', 'max:100'],
            'num_calle' => ['required', 'max:2'],
            'colonia' => ['required', 'max:100'],
            'codigo_postal' => ['required', 'max:5'],
            //'domicilio' => ['required','regex:/^(Calle)\s[\wÁÉÍÓÚáéíóú\s\.]{1,30}(\s\#\d{0,3}\s){0,1}(Colonia)\s[\wÁÉÍÓÚáéíóú\s\.]{1,30}(C)\.(P)\.\s[\d]{5}$/'],
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
            'calle.required' => 'Especifique la calle',
            'calle.max' => 'El campo "Calle" no puede superar los 100 caracteres',
            'num_calle.required' => 'Especifique el número de la calle o coloque "SN"',
            'num_calle.max' => 'El campo "Número" no puede superar los 2 caracteres',
            'colonia.required' => 'Especifique la colonia',
            'colonia.max' => 'El campo "Colonia" no puede superar los 100 caracteres',
            'codigo_postal.required' => 'Especifique el código postal',
            'codigo_postal.max' => 'El campo "Código Postal" no puede superar los 5 caracteres',
        ]);
        $newDonor = new Donor([
            'razon_social' => $request->razon_social,
            'tipo_persona' => $request->tipo_persona,
            'rfc' => $request->rfc,
            'nacionalidad' => $request->nacionalidad,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'celular' => $request->celular,
            'domicilio' => $request->calle."%.%".$request->num_calle."%.%".$request->colonia."%.%".$request->codigo_postal,
            'registrado_por' => auth('client')->user()->id,
        ]);
        $newDonor->save();
        return back()->with('success',"Donante registrado con éxito");
    }
    public function updateDonors(Request $request){
        $orgData = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->select('*')->first();

        if($request->razon_social != $orgData->razon_social){
            //Validation
            $validation = $request->validate([
                'razon_social' => ['required','unique:clients', 'max:200'],
            ],[
                'razon_social.required' => 'Especifique la razón social o nombre',
                'razon_social.unique' => 'Esta razón social ya se encuentra registrada',
                'razon_social.max' => "Campo 'Razón social' no puede superar los 200 caracteres",
            ]);
            //Updating
            $updating = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->update(['razon_social'=>($request->razon_social)]);
        }

        if($request->rfc != $orgData->rfc){
            //Validation
            $validation = $request->validate([
                'rfc' => ['required','unique:donors', 'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/'],
            ],[
                'rfc.required' => 'Especifique el RFC',
                'rfc.unique' => 'Este RFC ya se encuentra registrado',
                'rfc.regex' => 'Formato de RFC no reconocido, intente de nuevo',
            ]);
            //Updating
            $updating = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->update(['rfc'=>($request->rfc)]);
        }

        if($request->email != $orgData->email){
            //Validation
            $validation = $request->validate([
                'email' => ['required','email','unique:donors'],

            ],[
                'email.required' => 'Hace falta ingresar un email',
                'email.email' => 'Formato de email incorrecto/desconocido',
                'email.unique' => 'Este email ya se encuentra registrado',
            ]);
            //Updating
            $updating = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->update(['email'=>($request->email)]);
        }

        //Validation
        $validation = $request->validate([
            'nacionalidad' => ['required','regex:/^[A-Za-zÁÉÍÓÚáéíóú]{4,100}/'],
            'telefono' => ['required','regex:/^[0-9]{10}/'],
            'celular' => ['nullable','regex:/^[0-9]{10}/'],
            'calle' => ['required', 'max:100'],
            'num_calle' => ['required', 'max:2'],
            'colonia' => ['required', 'max:100'],
            'codigo_postal' => ['required', 'max:5'],
            //'domicilio' => ['required','regex:/^(Calle)\s[\w\s\.]{1,30}(\s\#\d{0,3}\s){0,1}(Colonia)\s[\wÁÉÍÓÚáéíóú\s\.]{1,30}(C)\.(P)\.\s[\d]{5}$/'],
        ],
        [
            'nacionalidad.required' => 'Campo "Nacionalidad" requerido',
            'nacionalidad.regex' => 'Nacionalidad no reconocida. Use sólo de 4 a 100 caracteres',
            'telefono.required' => 'Ingrese número de teléfono',
            'telefono.regex' => 'Formato de teléfono desconocido, ingrese 10 digitos',
            'celular.regex' => 'Formato de celular desconocido, ingrese 10 digitos',
            'calle.required' => 'Especifique la calle',
            'calle.max' => 'El campo "Calle" no puede superar los 100 caracteres',
            'num_calle.required' => 'Especifique el número de la calle o coloque "SN"',
            'num_calle.max' => 'El campo "Número" no puede superar los 2 caracteres',
            'colonia.required' => 'Especifique la colonia',
            'colonia.max' => 'El campo "Colonia" no puede superar los 100 caracteres',
            'codigo_postal.required' => 'Especifique el código postal',
            'codigo_postal.max' => 'El campo "Código Postal" no puede superar los 5 caracteres',
            //'domicilio.required' => 'Especifique la dirección',
            //'domicilio.regex' => 'Formato de dirección no válido. Ejemplo "Calle Independencia #3 Colonia Centro de la colonia C.P. 12345"',
        ]);
        //Updating
        $updating = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->update(['tipo_persona'=>($request->tipo_persona)]);
        $updating = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->update(['nacionalidad'=>($request->nacionalidad)]);
        $updating = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->update(['telefono'=>($request->telefono)]);
        $updating = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->update(['celular'=>($request->celular)]);
        $updating = DB::table('donors')->where('id', $request->id)->where('registrado_por', auth('client')->user()->id)->update(['domicilio'=>($request->calle."%.%".$request->num_calle."%.%".$request->colonia."%.%".$request->codigo_postal)]);

        return back()->with('success','Datos actualizados');

    }
    public function registerDonation(Request $request){
        //Validation
        $validation = $request->validate([
            'razon_social' => ['required', 'max:200'],
        ],[
            'razon_social.required' => 'Especifique la razón social o nombre',
            'razon_social.max' => "Campo 'Razón social' no puede superar los 200 caracteres",
        ]);
        $donorData = DB::table('donors')->where('razon_social', $request->razon_social)->where('registrado_por', auth('client')->user()->id)->select('*')->first();
        if($donorData != null){
            $validation = $request->validate([
                'cantidad' => ['required', 'regex:/^[1-9]+\d*(\.\d{1,2})*$/'],
            ],[
                'cantidad.required' => 'Especifique el monto del donativo',
                'cantidad.regex' => "Especifique el monto como un número entero o máximo con 2 decimales",
            ]);
            $newDonation = new Donation([
                'cantidad' => $request->cantidad,
                'fecha' => Carbon::now()->isoFormat('YYYY-MM-DD'),
                'donante' => $request->id,
                'cliente' => auth('client')->user()->id
            ]);
            $newDonation->save();
            return back()->with('success','Donación registrada');
        }else{
            return back()->withErrors(["Donante no registrado, para guardar el donativo registre primero al donante"]);
        }

    }
    public function registerRevenue(Request $request){
        //Validation
        $validation = $request->validate([
            'concepto' => ['required', 'min:20','max:100'],
        ],[
            'concepto.required' => 'Especifique el concepto de ingreso',
            'concepto.min' => "Campo 'Concepto' debe tener al menos 20 caracteres",
            'concepto.max' => "Campo 'Concepto' no puede superar los 100 caracteres",
        ]);
        
        $validation = $request->validate([
            'cantidad' => ['required', 'regex:/^[1-9]+\d*(\.\d{1,2})*$/'],
        ],[
            'cantidad.required' => 'Especifique el monto del ingreso',
            'cantidad.regex' => "Especifique el monto como un número entero o máximo con 2 decimales",
        ]);
        $new = new Revenue([
            'concepto' => $request->concepto,
            'cantidad' => $request->cantidad,
            'fecha' => Carbon::now()->isoFormat('YYYY-MM-DD'),
            'cliente' => auth('client')->user()->id
        ]);
        $new->save();
        return back()->with('success','Ingreso registrado');
            

    }
    public function registerExpense(Request $request){
        //Validation
        $validation = $request->validate([
            'concepto' => ['required', 'min:20','max:100'],
        ],[
            'concepto.required' => 'Especifique el concepto del gasto',
            'concepto.min' => "Campo 'Concepto' debe tener al menos 20 caracteres",
            'concepto.max' => "Campo 'Concepto' no puede superar los 100 caracteres",
        ]);
        
        $validation = $request->validate([
            'cantidad' => ['required', 'regex:/^[1-9]+\d*(\.\d{1,2})*$/'],
        ],[
            'cantidad.required' => 'Especifique el monto del gasto',
            'cantidad.regex' => "Especifique el monto como un número entero o máximo con 2 decimales",
        ]);
        $new = new Expense([
            'concepto' => $request->concepto,
            'cantidad' => $request->cantidad,
            'fecha' => Carbon::now()->isoFormat('YYYY-MM-DD'),
            'cliente' => auth('client')->user()->id
        ]);
        $new->save();
        return back()->with('success','Gasto registrado');
            

    }
}
