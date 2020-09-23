<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
