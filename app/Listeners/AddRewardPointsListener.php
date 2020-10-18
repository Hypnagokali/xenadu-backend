<?php

namespace App\Listeners;

use App\Events\GoalDoneEvent;
use App\RewardPoints;
use App\Goal;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddRewardPointsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(GoalDoneEvent $event)
    {
        $userId = $event->userId;
        $goalId = $event->goalId;
        $goal = Goal::find($goalId);
        $rewardPoints = new RewardPoints([
            'user_id' => $userId,
            'source_id' => $goalId,
            'source_name' => 'Goal-Monitor',
            'value' => $goal->workloadPoints->points_per_hour,
            'assigned_at' => new DateTime(),
        ]);
        $rewardPoints->save();
    }
}
