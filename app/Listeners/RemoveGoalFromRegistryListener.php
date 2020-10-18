<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\GoalRemovedEvent;
use App\GoalMonitorRegistry;

class RemoveGoalFromRegistryListener
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
    public function handle(GoalRemovedEvent $event)
    {
        $registry = GoalMonitorRegistry::where('goal_id', $event->goalId)->first();
        if (!empty($registry)) {
            $registry->delete();
        }
    }
}
