<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoalStateTransition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['goal_id', 'state', 'changed_on'];
}
