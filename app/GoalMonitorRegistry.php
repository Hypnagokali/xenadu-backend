<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoalMonitorRegistry extends Model
{
    public function goal()
    {
        return $this->belongsTo('App\Goal');
    }
}
