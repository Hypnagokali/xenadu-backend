<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function authenticate(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = ['email' => $email, 'password' => $password];
        /* auth helper should return json token */
        $token = auth()->attempt($credentials);

        if (!$token) {
            return response('Kein Zutritt', 401);
        }
        return response()->json([
            'token' => $token
        ]);
    }
}
