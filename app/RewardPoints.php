<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardPoints extends Model
{
    protected $fillable = ['user_id', 'source_id', 'source_name', 'value', 'assigned_at'];
}
