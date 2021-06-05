<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class ApiController
{
    /*
    * @param $request
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
        }

        if (Auth::attempt($validator->valid())) {
            return User::where('email', '=', $validator->valid()['email'])->first();
        }

        abort(401);
    }

    /*
    * @param $request
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
        }

        return User::create($validator->valid());
    }
}
