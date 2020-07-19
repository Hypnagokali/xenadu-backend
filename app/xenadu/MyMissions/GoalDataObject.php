<?php

namespace Xenadu\MyMissions;

use App\Week;
use App\WorkloadPoints;
use App\Goal;

class GoalDataObject
{
    public $id;
    public $name;
    public $description;
    public $addedOn;
    public $week;
    public $workloadPoints;

    public function __construct(Goal $goal, Week $week, WorkloadPoints $points)
    {
        $this->id = $goal->id;
        $this->name = $goal->name;
        $this->description = $goal->description;
        $this->addedOn = $goal->added_on;
        $this->week = $week;
        $this->workloadPoints = $points;
    }
}
