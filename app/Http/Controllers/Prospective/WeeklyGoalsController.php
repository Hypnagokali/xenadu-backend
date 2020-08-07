<?php

namespace App\Http\Controllers\Prospective;

use App\Goal;
use App\GoalState;
use App\GoalStateTransition;
use App\Http\Controllers\Controller;
use Xenadu\MyMissions\WeekCalculator;
use App\Week;
use App\WorkloadPoints;
use DateTime;
use Illuminate\Http\Request;
use Xenadu\MyMissions\GoalCollection;
use Xenadu\MyMissions\GoalDataObject;
use Xenadu\MyMissions\OverdueGoalsResponse;
use Xenadu\MyMissions\OverviewResponse;
use Xenadu\MyMissions\WeeklyGoalsResponse;

class WeeklyGoalsController extends Controller
{

    private $userId;

    public function __construct()
    {
        $this->middleware('auth:api');
        //phpcs:disable
        if (!auth()->user()) return false;
        //phpcs:enable
        $this->userId = auth()->user()->id;
    }

    private function fastPreparation($input)
    {
        $trimed = trim($input);
        $stripped = strip_tags($trimed);
        $final = htmlspecialchars($stripped);

        return $final;
    }

    private function validateGoalData(
        Request $request,
        $cwRule = 'required|numeric|min:0|max:4',
        $nameRule = 'required|max:64',
        $descriptionRule = 'required|max:255',
        $workloadLevelRule = 'required|numeric'
    ) {
        $highestWorkloadLevel = WorkloadPoints::highestWorkloadLevel();

        $validatedData = $request->validate([
            'name' => $nameRule,
            'description' => $descriptionRule,
            'cw' => $cwRule,
            'workload_level' => $workloadLevelRule . "|max:$highestWorkloadLevel",
        ]);

        return $validatedData;
    }

    /**
     * deleteGoal
     *
     * @param  Integer $id
     * @return void
     */
    public function deleteGoal($id)
    {
        // only delete goal if it belongs to user
        $goal = Goal::find($id);

        if ($goal === null) {
            return $this->errorResponse('Ressource not found or already deleted', 404);
        }
        if (!($goal->user_id === $this->userId)) {
            return $this->errorResponse('Not allowed', 401);
        }

        $goal->delete();
        return $this->jsonResponse(['id' => $id]);
    }

    /**
     * updateGoal
     *
     * @param  Integer $id
     * @param  Request $request
     * @return GoalDataObject
     */
    public function updateGoal($id, Request $request)
    {
        // cw data is in this case a real weeknumber and not a value that represents a weeknumber
        $calendarWeekValRules = 'required|numeric|min:1|max:55';
        $validatedData = $this->validateGoalData($request, $calendarWeekValRules);

        $name = $this->fastPreparation($validatedData['name']);
        $description = $this->fastPreparation($validatedData['description']);
        $cw = $this->fastPreparation($validatedData['cw']);
        $workloadLevel = $this->fastPreparation($validatedData['workload_level']);

        $goal = Goal::find($id);

        // get data objects by input values
        $year = $goal->week->year;
        $week = Week::firstOrCreate(['cw' => $cw, 'year' => $year]);
        $workloadPoints = WorkloadPoints::where('level', $workloadLevel)->first();

        $goal->scheduleFor($week);
        $goal->setWorkload($workloadPoints);
        $goal->name = $name;
        $goal->description = $description;
        $goal->save();
        $goalResponse = new GoalDataObject($goal, $week, $workloadPoints);
        return $this->jsonResponse($goalResponse);
    }

    /**
     * createGoal
     *
     * Creates a goal ressource.
     *
     * @param  Request $request
     * @return Response a GoalDataObject.
     */
    public function createGoal(Request $request)
    {
        $highestWorkloadLevel = WorkloadPoints::highestWorkloadLevel();

        $validatedData = $request->validate([
            'name' => 'required|max:64',
            'description' => 'required|max:255',
            'cw' => 'required|numeric|min:0|max:4',
            'workload_level' => "required|numeric|max:$highestWorkloadLevel",
        ]);

        $userId = auth()->user()->id;
        $name = $this->fastPreparation($validatedData['name']);
        $description = $this->fastPreparation($validatedData['description']);
        $weekValue = $this->fastPreparation($validatedData['cw']);
        $workload = $this->fastPreparation($validatedData['workload_level']);

        $cw = WeekCalculator::weekValueToCW($weekValue);
        $now = new DateTime();
        $addedOn = $now->format('Y-m-d H:i:s');
        $year = $now->format('Y');
        $week = Week::firstOrCreate(['cw' => $cw, 'year' => $year]);
        $points = WorkloadPoints::where('level', $workload)->first();

        $state = GoalState::where('name', 'todo')->first();

        $goal = new Goal([
            'user_id' => $userId,
            'name' => $name,
            'description' => $description,
            'added_on' => $addedOn
        ]);
        $goal->scheduleFor($week);
        $goal->setWorkload($points);
        $goal->save();

        $goal->setState($state);

        $goalResponse = new GoalDataObject($goal, $week, $points);
        return $this->jsonResponse($goalResponse, 201);
    }


    /**
     * getGoalFromWeek
     *
     * @param  int $weekValue 0=current, 1=next, 2=nextafternext, 3=in3weeks
     * @return GoalCollection
     */
    private function getGoalsFromWeek(int $weekValue)
    {
        // finde Woche
        $dateTime = new DateTime();
        $year = $dateTime->format('Y');
        $cw = WeekCalculator::weekValueToCW($weekValue);
        $week = Week::firstOrCreate(['cw' => $cw, 'year' => $year]);

        // finde Ziele zu Woche
        $goals = Goal::where(['week_id' => $week->id, 'user_id' => $this->userId])->orderBy('added_on', 'desc')->get();

        // $goalDataObjects = [];
        $goalCollection = new GoalCollection(WeekCalculator::getWeekNameFromValue($weekValue), $cw, $year);

        foreach ($goals as $goal) {
            if (!$goal->trashed()) {
                // get week
                $week = $goal->week;
                // get points
                $points = $goal->workloadPoints;
                // set dataobject
                $goalCollection->addGoal(new GoalDataObject($goal, $week, $points));
            }
        }
        // $res = new WeeklyGoalsResponse($goalCollection);
        return $goalCollection;
        // return $goalDataObjects;
    }

    /**
     * getOverview
     *
     * @return WeeklyGoalsResponse
     */
    private function getOverview()
    {
        $current = $this->getGoalsFromWeek(0);
        $next = $this->getGoalsFromWeek(1);
        $nextAfterNext = $this->getGoalsFromWeek(2);
        $inThreeWeeks = $this->getGoalsFromWeek(3);

        $overview = new WeeklyGoalsResponse($current);
        $overview->add($next);
        $overview->add($nextAfterNext);
        $overview->add($inThreeWeeks);
        return $overview;
    }

    private function getOverdueGoals()
    {
        $now = new DateTime();
        $currentCW = $now->format('W');
        $thisYear = $now->format('Y');
        $goals = Goal::where(['user_id' => $this->userId, 'deleted_at' => null])->get();

        $overdues = $goals->reject(function ($goal) use ($currentCW) {
            // does not work for different years. I have to fix that!
            return $goal->week->cw >= $currentCW || $goal->currentState->name === 'done';
        });

        $goalCollection = new GoalCollection('overdue', intval($currentCW), intval($thisYear));

        foreach ($overdues as $goal) {
            $week = $goal->week;
            $points = $goal->workloadPoints;
            $goalCollection->addGoal(new GoalDataObject($goal, $week, $points));
        }

        return $goalCollection;
    }

    /**
     * showGoals
     *
     * Returns a response with all goals that are scheduled for the requested week
     *
     * Route (e.g.) 'week/next/goals' or 'week/nextafternext/goals'
     *
     * @param  string $week_name relative name of week: current, next, nextAfterNext
     * @param  Request $request
     * @return Response an array of GoalDataObjects.
     */
    public function showGoals($week_name, Request $request)
    {
        switch ($week_name) {
            case 'current':
                $res = new WeeklyGoalsResponse($this->getGoalsFromWeek(0));
                return $this->jsonResponse($res);
                break;
            case 'next':
                $res = new WeeklyGoalsResponse($this->getGoalsFromWeek(1));
                return $this->jsonResponse($res);
                break;
            case 'overview':
                /* getOverview already returns a WeeklyGoalsResponse */
                return $this->jsonResponse($this->getOverview());
                break;
            case 'overdue':
                $res = new WeeklyGoalsResponse($this->getOverdueGoals());
                return $this->jsonResponse($res);
        }
    }

    private function isCorrectState(string $state)
    {
        $possibleGoalStates = GoalState::all();
        foreach ($possibleGoalStates as $possibleGoalState) {
            if ($possibleGoalState->name === $state) {
                return true;
            }
        }
        return false;
    }

    public function setState($id, string $newState)
    {

        if (!$this->isCorrectState($newState)) {
            return $this->errorResponse('State not found', 404);
        };

        $goal = Goal::find($id);
        $state = GoalState::where('name', $newState)->first();
        $goal->setState($state);
        $goal->save();

        // Response: GoalData
        $week = $goal->week;
        $workloadPoints = $goal->workloadPoints;
        $goalDataObject = new GoalDataObject($goal, $week, $workloadPoints);
        return $this->jsonResponse($goalDataObject);
    }
}
