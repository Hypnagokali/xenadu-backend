<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoalMonitorPushMotivation extends Model
{
    protected $fillable = ['pusher_id', 'user_id', 'goal_id'];
}
