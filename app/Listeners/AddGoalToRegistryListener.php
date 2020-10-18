<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\GoalCreatedEvent;
use App\GoalMonitorRegistry;

class AddGoalToRegistryListener
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
    public function handle(GoalCreatedEvent $event)
    {
        $goalMonitorRegistry = new GoalMonitorRegistry();
        $goalMonitorRegistry->user_id = $event->userId;
        $goalMonitorRegistry->goal_id = $event->goalId;
        $goalMonitorRegistry->save();
    }
}
