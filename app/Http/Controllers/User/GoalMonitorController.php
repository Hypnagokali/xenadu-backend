<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Xenadu\Monitor\PublicGoalDataObject;
use DateTime;
use Xenadu\MyMissions\WeekCalculator;
use App\Week;
use App\Goal;
use App\GoalMonitorRegistry;
use Illuminate\Database\Eloquent\Collection;
use Xenadu\MyMissions\GoalCollection;
use Xenadu\MyMissions\GoalDataBasicObject;
use Xenadu\MyMissions\GoalDataObject;
use Xenadu\MyMissions\WeeklyGoalsResponse;

class GoalMonitorController extends Controller
{

    private $userId;

    public function __construct()
    {
        $this->middleware('auth:api');

        //phpcs:disable
        if (!auth()->user()) return false;
        //phpcs:enable
        $this->userId = auth()->user()->id;
        $this->middleware('refresh');
    }

    /**
     * getOverdueGoals
     */
    private function getOverdueGoals($userId)
    {
        $now = new DateTime();
        $currentCW = $now->format('W');
        $thisYear = $now->format('Y');

        $registeredGoals = GoalMonitorRegistry::where('user_id', $userId)->get();

        $goals = [];

        foreach ($registeredGoals as $registeredGoal) {
            $goal = Goal::where(['id' => $registeredGoal->goal_id, 'deleted_at' => null])->first();
            $goals []= $goal;
        }

        // $goals = Goal::where(['user_id' => $this->userId, 'deleted_at' => null])->get();

        $goalsColl = new Collection($goals);


        $overdues = $goalsColl->reject(function ($g) use ($currentCW) {
            // does not work for different years. I have to fix this!
            return $g->week->cw >= $currentCW || $g->currentState->name === 'done';
        });

        $goalCollection = new GoalCollection('overdue', intval($currentCW), intval($thisYear));

        foreach ($overdues as $goal) {
            $week = $goal->week;
            $points = $goal->workloadPoints;
            $goalCollection->addGoal(new PublicGoalDataObject($goal, $week, $points));
        }

        return $goalCollection;
    }

    /**
     * getGoalCollectionFromMonitor
     *
     * @param  int $weekValue 0=current, 1=next, 2=nextafternext, 3=in3weeks, -1 = overdue
     * @return GoalCollection
     */
    private function getGoalCollectionFromMonitor($userId, $weekValue = 0)
    {
        // finde Woche
        $dateTime = new DateTime();
        $year = $dateTime->format('Y');
        $cw = WeekCalculator::weekValueToCW($weekValue);

        // finde Ziele im Monitor
        $registeredGoals = GoalMonitorRegistry::where('user_id', $userId)->get();
        $goals = [];
        foreach ($registeredGoals as $registeredGoal) {
            $goal = Goal::find($registeredGoal->goal_id);
            if ($goal->week->cw == $cw) {
                $goals []= $goal;
            }
        }

        // $goalDataObjects = [];
        $goalCollection = new GoalCollection(WeekCalculator::getWeekNameFromValue($weekValue), $cw, $year);

        foreach ($goals as $goal) {
            if (!$goal->trashed()) {
                // get week
                $week = $goal->week;
                // get points
                $points = $goal->workloadPoints;
                // set dataobject
                $goalCollection->addGoal(new PublicGoalDataObject($goal, $week, $points));
            }
        }
        return $goalCollection;
    }

    private function goalsFromWeek($userId, $weekValue = 0)
    {
        if ($weekValue >= 0) {
            return $this->getGoalCollectionFromMonitor($userId, $weekValue);
        }
        return $this->getOverdueGoals($userId);

    }

    public function findGoalsFromUser($userId)
    {
        if (auth()->user()->isFriend($userId)) {
            // friend data
        } else {
            // public data
        }
        $overview = new WeeklyGoalsResponse($this->goalsFromWeek($userId, 0));
        $overview->add($this->goalsFromWeek($userId, 1));
        $overview->add($this->goalsFromWeek($userId, 2));
        $overview->add($this->goalsFromWeek($userId, 3));
        $overview->add($this->goalsFromWeek($userId, -1));
        // $overview = [];
        // $overview []= $this->goalsFromWeek($userId, 0);
        // $overview []= $this->goalsFromWeek($userId, 1);
        // $overview []= $this->goalsFromWeek($userId, 2);
        // $overview []= $this->goalsFromWeek($userId, 3);
        // $overview [] = $this->goalsFromWeek($userId, -1);
        return $this->jsonResponse($overview);
    }
}
