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
        $link = config('app.url') .'/'. 'reestablecercontraseña/' . $token . '?email=' . urlencode($user->email);

            try {
                \Mail::to($email)->send(new \App\Mail\RequestResetPassword($link));
                return true;
            } catch (\Exception $e) {
                return back()->withErrors($e->getMessage());
            }
    }
    public function showResetPassword(Request $request){
        return view('ResetPassword.ResetPassword')->with('token', $request->token)->with('oldEmail', $request->email);
    }
    public function updatePassword(Request $request){
        $validateData = $request->validate([
            'email' => ['bail','required', 'same:oldEmail'],
            'password' => ['bail','required', 'max:255', 'same:passwordconfirmation', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\ ^&\*]).{6,}$/']
        ],[
            'email.required' => 'Ingrese su email por favor',
            'email.same' => 'El email ingresado no corresponde con el email de esta cuenta',
            'password.same' => 'La contraseña ingresada no corresponde con su confirmación',
            'password.max:255' => 'La contraseña no debe superar los 255 caracteres',
            'password.regex' => 'La contraseña debe ser de al menos 6 caracteres e incluir al menos un número, un caracter especial y una letra mayúscula',
        ]);
        $data = DB::table('clients')->where('email', $request->get('email'))->select('id');
        $updating = DB::table('clients')->where('email', $request->get('email'))->update(['contraseña'=>(bcrypt($request->get('password')))]);            
        
        DB::table('password_resets')->where('token', $request->token)->delete();
        return back()->with('success',"éxito");
    }
}
