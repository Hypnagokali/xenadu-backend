<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkloadPoints extends Model
{
    protected $table = 'workload_points';

    public static function highestWorkloadLevel()
    {
        $allEntries = self::all();
        $highestLevel = 0;
        foreach ($allEntries as $entry) {
            if ($entry->level > $highestLevel) {
                $highestLevel = $entry->level;
            }
        }
        return $highestLevel;
    }
}
