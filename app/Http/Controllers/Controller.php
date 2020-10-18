<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function jsonResponse($data, $statusCode = 200)
    {
        $headers = [
            'MY-HEADER' => 'MAUS',
            'Content-Type' => 'application/json; charset=utf8',
            'Access-Control-Allow-Origin' => '*'
        ];

        return response()->json($data, $statusCode, $headers, JSON_UNESCAPED_UNICODE);
    }

    public function errorResponse($message, $statusCode = 400)
    {
        $headers = [
            'Content-Type' => 'text/html',
            'Access-Controll-Allow-Origin' => '*'
        ];
        return response($message, $statusCode)->withHeaders($headers);
    }
}
