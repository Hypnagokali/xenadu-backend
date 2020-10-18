<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\GoalCreatedEvent;
use App\Listeners\AddGoalToRegistryListener;
use App\Events\GoalRemovedEvent;
use App\Listeners\AddRewardPointsListener;
use App\Listeners\RemoveGoalFromRegistryListener;
use App\Events\GoalDoneEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        GoalDoneEvent::class => [
            AddRewardPointsListener::class,
        ],
        GoalRemovedEvent::class => [
            RemoveGoalFromRegistryListener::class,
        ],
        GoalCreatedEvent::class => [
            AddGoalToRegistryListener::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
