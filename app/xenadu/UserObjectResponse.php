<?php
namespace Xenadu;

use App\User;
/**
 * UserObjectResponse
 *
 * For different response data either the user is a friend or is not.
 */
class UserObjectResponse
{

    public $isFriend = false;
    public $isPending = false;
    public $user = null;

    public function __construct(User $user)
    {
        $this->isFriend = $user->isFriend(auth()->user()->id);
        $this->isPending = $user->isRequestPendingFromMe();
        $this->user = $user;
    }
}
