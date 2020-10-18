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
    public $hasRequestedMe = false;
    public $rewardPointsSum = 0;
    public $user = null;

    public function __construct(User $user)
    {
        $this->isFriend = $user->isFriend(auth()->user()->id);
        $this->isPending = $user->isRequestPendingFromMe();
        $this->hasRequestedMe = $user->hasRequestedMe();
        $this->user = $user;
        $this->user->rewardPointsSum = $user->rewardPoints->sum('value');
        $this->user->doneGoalCount = $user->goals->where('current_state_id', 2)->count('id');
    }
}
