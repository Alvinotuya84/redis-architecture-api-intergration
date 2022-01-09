<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AuthController extends Controller
{
    //
    public function register(Request $request){
        $fields=$request->validate([
           'name'=>'required',
           'email'=>'required|unique:users,email',
           'password'=>'required'
        ]);
        $user=User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']) 
        ]);
        $token=$user->createToken('usertoken')->plainTextToken;
        $response=[
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,201);


    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return[
            'message'=>'Logged out'
        ];
    }


    public function login(Request $request){
        $fields=$request->validate([
           'email'=>'required',
           'password'=>'required'
        ]);

        $user=User::where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return[
                'message'=>'the credentials entered do not match ones in the system'
            ];

        }
        $token=$user->createToken('usertoken')->plainTextToken;
        $response=[
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,201);


    }
}
