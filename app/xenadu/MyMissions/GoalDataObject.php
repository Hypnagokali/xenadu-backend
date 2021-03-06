<?php

namespace Xenadu\MyMissions;

use App\Week;
use App\WorkloadPoints;
use App\Goal;

class GoalDataObject extends GoalDataBasicObject
{
    public $id;
    public $name;
    public $addedOn;
    public $state;
    public $isRegistered;
    public $week;
    public $workloadPoints;

    /**
     * @var String
     * private Informationen
     */
    public $description;

    public function __construct(Goal $goal, Week $week, WorkloadPoints $points)
    {
        parent::__construct($goal, $week, $points);
        $this->description = $goal->description;
    }
}
