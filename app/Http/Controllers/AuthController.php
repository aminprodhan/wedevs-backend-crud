<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function store(Request $req){
        $res["status"]=0;$res["msg"]="Something went wrong!!";
        $validate=$req->validate([
            'name' => "required|max:255",
            'email' => "email|required|unique:users",
            'password' => "required",
        ]);
        $validate["password"]=bcrypt($req->password);
        $user=User::create($validate);
        $accessToken=$user->createToken('authToken')->accessToken;

        $res["status"]=1;$res["msg"]="Success";
        $res["user"]=["data" => $user,"access_token"=>$accessToken];
        return response()->json($res);
    }
    public function login(Request $req){
        $res["status"]=0;$res["msg"]="Invalid Credentials";
        $loginData=$req->validate([
            'email' => "email|required",
            'password' => "required",
        ]);
        if(auth()->attempt($loginData)){
            $accessToken=auth()->user()->createToken('authToken')->accessToken;
            $res["status"]=1;$res["msg"]="Success";
            $res["user"]=["data" => auth()->user(),"access_token"=>$accessToken];
        }
        return response()->json($res);
    }
}
