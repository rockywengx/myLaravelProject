<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\ResponseField;

/**
 * @group Auth
 */

#[BodyParam(name:'email', description:'用戶的電子郵件地址')]
#[BodyParam(name:'password', description:'用戶的密碼')]
#[ResponseField(name:'token', description:'用於訪問其他端點的令牌')]


class AuthController extends Controller
{
    //
    public function login(Request $request){
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)){
            $token = $request->user()->createToken('authToken')->plainTextToken;
            return response()->json(['token'=>$token],200);
        }

        return response()->json(['message'=>'Unauthorized'],401);
    }


}
