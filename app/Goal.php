<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\GoalStateTransition;
use DateTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'name', 'description', 'added_on'];

    public function week()
    {
        return $this->belongsTo('App\Week');
        // return $this->hasOne('App\Week');
    }

    public function workloadPoints()
    {
        return $this->belongsTo('App\WorkloadPoints');
    }

    public function scheduleFor(Week $week)
    {
        $this->week_id = $week->id;
    }

    public function setWorkload(WorkloadPoints $workload)
    {
        $this->workload_points_id = $workload->id;
    }

    public function currentState()
    {
        return $this->belongsTo('App\GoalState');
    }

    public function setState(GoalState $goalState)
    {
        $now = new DateTime();
        $state = $goalState;
        $trans = GoalStateTransition::create([
            'goal_id' => $this->id,
            'state' => $state->name,
            'changed_on' => $now
        ]);
        $this->current_state_id = $state->id;
    }

    public function getState()
    {
        return $this->currentState;
        // $trans = GoalStateTransition::where('goal_id', $this->id)->orderBy('changed_on', 'desc')->first();
        // return $trans;
    }
}
