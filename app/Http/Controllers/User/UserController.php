<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;
use App\User;

class UserController extends Controller
{

    private $userId;

    public function __construct()
    {
        $this->middleware('auth:api');

        // phpcs:disable
        if (!auth()->user()) return false;
        // phpcs:enable

        $this->userId = auth()->user()->id;
        $this->middleware('refresh');
    }

    public function findAllUsers()
    {
        return $this->jsonResponse(User::all());
    }

    public function findAllFriends()
    {
        return $this->jsonResponse(auth()->user()->findAll());
    }


    public function acceptFriendship($userId)
    {
        $newFriend = auth()->user()->accept($userId);
        if ($newFriend === null) {
            return $this->errorResponse('User not found', 404);
        }
        return $this->jsonResponse($newFriend);
    }

    public function addFriend($userId)
    {
        try {
            $newFriend = auth()->user()->addFriend($userId);
        } catch (Exception $e) {
            return $this->errorResponse('User not found', 404);
        }

        return $this->jsonResponse($newFriend);
    }

    public function isFriend($userId)
    {
        $state = auth()->user()->isFriend($userId);
        if ($state) {
            return ['friend' => true];
        }
        return ['friend' => false];
    }

    public function hello($id)
    {
        $user = auth()->user();
        return $user->hello();
    }
}
