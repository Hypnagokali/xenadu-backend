<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $payload = JWTAuth::parseToken()->getPayload();
        $nowMillis = time();
        $iat = $payload['iat'];

        $exp = $payload['exp'];

        $diff = $exp - $nowMillis;
        //$diff = $nowMillis - $iat;
        $refreshTime = 3600; // 60 * 60 (Jede Stunde ein refresh, Token ist 24 Stunden gültig)

        $response->headers->set("Access-Control-Expose-Headers", ["Token-TikTok", "Token-Refresh-At"]);
        // $response->headers->set("Access-Control-Expose-Headers", "Token-Refresh-At");
        $response->headers->set("Token-TikTok", $diff);
        $response->headers->set("Token-Refresh-At", $refreshTime);

        // 60 Sekunden => 60*60 => 1 Stunde
        if ($diff <= $refreshTime) {
            try {
                $newToken = JWTAuth::refresh(JWTAuth::getToken(), false, true);
                // $response->headers->set("Access-Control-Expose-Headers", "Refresh-Token");
                $response->headers->set("Access-Control-Expose-Headers", ["Token-TikTok", "Token-Refresh-At", "Refresh-Token"]);
                $response->headers->set("Refresh-Token", $newToken);
                $response->headers->set("Authorization", $newToken);
            } catch (Exception $e) {
                // ToDo
                // Logger::log("Exception")
                return $response;
            }
        }

        return $response;
    }
}
