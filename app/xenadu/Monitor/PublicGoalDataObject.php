<?php

namespace Xenadu\Monitor;


use App\Goal;
use App\Week;
use App\WorkloadPoints;
use Xenadu\MyMissions\GoalDataBasicObject;

class PublicGoalDataObject extends GoalDataBasicObject
{
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
