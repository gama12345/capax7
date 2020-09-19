<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    //Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email',
            'password' => 'required',
        ],[
            'email.email' => 'Formato de email no reconocido',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        } else {
            $credentials = $request->only('email', 'password');
            if (Auth::guard('admin')->attempt($credentials)) {
                return redirect()->route('home', ['user' => 'admin']);
            }else if (Auth::guard('client')->attempt($credentials)) {
                return redirect()->route('home', ['user' => 'client']);
            }else{
                return back()->withErrors(["noLogin"=>"Usuario o contraseña no válidos"]);
            }
        }
    }
    //After Login
    public function goHome(Request $request)
    {
        if(auth('admin')->check()){
            return view('admin.home');
        }else if(auth('client')->check()){
            return view('client.home');
        }else{
            return redirect()->route('main');
        }
    }
    //Logout
    public function logout(Request $request){
        if($request->user === 'admin'){
            auth('admin')->logout();
        }else{
            auth('client')->logout();
        }
        return redirect()->route('main');
    }

    //Reset password
    public function showRequestPassword(){
        return view('resetpassword.sendrequest');
    }
    public function validateRequestPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required','email',
        ],[
            'email.required' => 'Ingrese su email antes de continuar',
            'email.email' => 'Formato de email no reconocido',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        } else {
            $user = DB::table('clients')->where('email', $request->email)->first();
            if($user){
                //Creamos el token para reestablecer la contraseña
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => Str::random(20),
                    'created_at' => Carbon::now()
                ]);

                //Obtenemos el token registrado anteriormente
                $tokenData = DB::table('password_resets')
                    ->where('email', $request->email)->first();

                if ($this->sendEmailResetPassword($request->email, $tokenData->token)) {
                    return back()->with('success', 'Se ha enviado un enlace a su correo para reestalecer su contraseña');
                } else {
                    return back()->with('failed', 'Ha ocurrido un error en el servidor, pruebe nuevamente o contactenos directamente');
                }
            }else{
                return back()->withErrors('No se encontró ninguna cuenta registrada con este email');
            }
        }
    }
    private function sendEmailResetPassword($email, $token){
        //Obtenemos info del usuario
        $user = DB::table('clients')->where('email', $email)->select('razon_social', 'email')->first();
        //Generamos el enlace de reestablecimiento
        $link = config('app.url') . 'reestablecercontraseña/' . $token . '?email=' . urlencode($user->email);

            try {
                \Mail::to($email)->send(new \App\Mail\RequestResetPassword($link));
                return true;
            } catch (\Exception $e) {
                return false;
            }
    }
}
