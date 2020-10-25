<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoalMonitorComment extends Model
{
    protected $fillable = ['user_id', 'commenting_user_id', 'content', 'goal_id', 'posted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function commentingUser()
    {
        return $this->belongsTo('App\User');
    }
}
