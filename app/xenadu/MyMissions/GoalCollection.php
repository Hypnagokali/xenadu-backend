<?php
namespace Xenadu\MyMissions;

use DateTime;

class GoalCollection
{
    /**
     * @var array
     */
    public $date;

    /**
     * @var string
     */
    public $collectionName;

    /**
     * @var array
     */
    public $goals;

    public function __construct(string $collectionName, int $calendarWeek, int $year, array $goals = [])
    {
        $start = new DateTime();
        $end = new DateTime();
        $start->setISODate($year, $calendarWeek);
        $end->setISODate($year, $calendarWeek, 7);
        $this->date = [
            'cw' => $calendarWeek,
            'from' => $start,
            'to' => $end
        ];
        $this->collectionName = $collectionName;
        $this->goals = $goals;
    }

    public function addGoal(GoalDataObject $goal)
    {
        $this->goals []= $goal;
    }
}
