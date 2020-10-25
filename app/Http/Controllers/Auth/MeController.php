<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{

    public function __construct()
    {
        //echo "here we must have middleware action!<br>";
        // $this->middleware('refresh');
        $this->middleware('auth:api');
    }


    public function home()
    {
        $user = Auth::user();
        $username = $user->name;
        echo "Hello, its $username's homepage! <br>";
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'rewardPointsSum' => $user->rewardPoints->sum('value'),
            'doneGoalCount' => $user->goals->where('current_state_id', 2)->count('id'),
        ]);
    }

    public function login()
    {
        dd("hello out there");
    }

    /*
    public function __invoke()
    {
        dd("Hello, its me signing in");
    }*/
}
