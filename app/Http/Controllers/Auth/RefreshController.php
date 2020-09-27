<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshController extends Controller
{

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshJwtToken()
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $exp = $payload['exp'];
        $iat = $payload['iat'];
        $diff = $exp - $iat;
        // ToDo: JWTExceptions implementieren
        return $this->respondWithToken(auth()->refresh(), ['exp' => $exp, 'iat' => $iat, 'diff' => $diff]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $infos)
    {
        return response()->json([
            'token' => $token,
            'exp' => $infos['exp'],
            'iat' => $infos['iat'],
            'diff' => $infos['diff']
        ]);
    }
}
