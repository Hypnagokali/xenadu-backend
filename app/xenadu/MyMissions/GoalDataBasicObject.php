<?php

namespace Xenadu\MyMissions;

use App\Week;
use App\WorkloadPoints;
use App\Goal;

class GoalDataBasicObject
{
    public $id;
    public $name;
    public $addedOn;
    public $state;
    public $isRegistered;
    public $week;
    public $workloadPoints;
    public $pushMotivations;

    public function __construct(Goal $goal, Week $week, WorkloadPoints $points)
    {
        $this->id = $goal->id;
        $this->name = $goal->name;
        $this->addedOn = $goal->added_on;
        $this->isRegistered = $goal->isRegistered();

        $this->state = $goal->currentState->name;

        $this->week = $week;
        $this->workloadPoints = $points;

        $this->pushMotivations = $goal->pushMotivations->count();
    }
}
