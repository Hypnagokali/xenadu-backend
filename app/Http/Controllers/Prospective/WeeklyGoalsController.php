<?php

namespace App\Http\Controllers\Prospective;

use App\Goal;
use App\Http\Controllers\Controller;
use Xenadu\MyMissions\WeekCalculator;
use App\Week;
use App\WorkloadPoints;
use DateTime;
use Illuminate\Http\Request;
use Xenadu\MyMissions\GoalDataObject;

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

        $goal = new Goal([
            'user_id' => $userId,
            'name' => $name,
            'description' => $description,
            'added_on' => $addedOn
        ]);
        $goal->scheduleFor($week);
        $goal->setWorkload($points);
        $goal->save();

        $goalResponse = new GoalDataObject($goal, $week, $points);
        return $this->jsonResponse($goalResponse, 201);
    }

    private function getGoalsFromCurrentWeek()
    {
        // finde Woche
        $dateTime = new DateTime();
        $year = $dateTime->format('Y');
        $cw = WeekCalculator::weekValueToCW(0);
        $week = Week::where(['cw' => $cw, 'year' => $year])->first();
        // finde Ziele zu Woche
        $goals = Goal::where(['week_id' => $week->id, 'user_id' => $this->userId])->orderBy('added_on', 'desc')->get();
        $goalDataObjects = [];
        foreach ($goals as $goal) {
            // get week
            $week = $goal->week;
            // get points
            $points = $goal->workloadPoints;
            // set dataobject
            $goalDataObjects[] = new GoalDataObject($goal, $week, $points);
        }
        return $goalDataObjects;
    }

    public function showGoals($week_name, Request $request)
    {
        switch ($week_name) {
            case 'current':
                return $this->jsonResponse($this->getGoalsFromCurrentWeek());
                break;
            case 'next':
                dd('Nächste Woche');
                break;
        }
    }
}
