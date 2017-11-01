<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth; 

class UsersController extends Controller
{
    //
    public function index(){
        
        if (Auth::user()->type != 'Admin'){
           return redirect('/'); 
        }
        return view('pages/account');
    }
        
        
    public function save(Request $request){

        if (Auth::user()->type != 'Admin'){
           return redirect('/'); 
        }
        
        $date = date('Y-m-d H:i:s');
        //$request->all();
        $name = $request->input('name');
        $email = $request->input('email');
        $type = $request->input('type');
        $password = "abcd1234";
        
        User::create([
            'name' => $name,
            'email' => $email,
            'type' => $type,
            'password' => bcrypt($password),
        ]);
        
        return redirect('/');
    }
}
