<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
