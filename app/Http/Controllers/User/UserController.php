<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;
use App\User;
use Xenadu\UserObjectResponse;

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

    public function findUserById($userId)
    {
        $user = User::find($userId);
        if ($user !== null) {
            return $this->jsonResponse(new UserObjectResponse($user));
        }
        return $this->errorResponse('User nicht gefunden', 404);
    }

    // public function findAllUsers()
    // {
    //     return $this->jsonResponse(User::all());
    // }

    public function findAllFriends()
    {
        return $this->jsonResponse(auth()->user()->findAll());
    }

    public function deleteFriendship($userId)
    {
        $success = auth()->user()->unfriend($userId);
        if ($success) {
            return $this->jsonResponse('Freundschaft gelöscht', 202);
        }
        return $this->errorResponse('Fehler beim löschen der Freundschaft', 404);
    }

    public function findPendingRequestsFromMe()
    {
        return auth()->user()->findAllPendingRequestsFromMe();
    }

    public function findPendingRequestsToMe()
    {
        return auth()->user()->findAllPendingRequestsToMe();
    }

    public function denyFriendship($userId)
    {
        $success = auth()->user()->deny($userId);
        if ($success) {
            return $this->jsonResponse('Freundschaftsanfrage gelöscht', 202);
        }
        return $this->errorResponse('Fehler beim löschen der Freundschaftsanfrage', 404);
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
