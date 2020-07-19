<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
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
}
