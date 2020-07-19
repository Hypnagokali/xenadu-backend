<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $fillable = ['cw', 'year'];

    public function goals()
    {
        return $this->hasMany('App\Goal');
    }
}
