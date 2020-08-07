<?php
namespace Xenadu\MyMissions;

use DateTime;
use Exception;

class WeekCalculator
{

    const WEEK_NAMES = [
        0 => 'current',
        1 => 'next',
        2 => 'nextAfterNext',
        3 => 'inThreeWeeks'
    ];
    /*
        Temporary helper function to calc the cw
    */
    private static function calcCW($days)
    {
        // way too complex
        // just calculating the current week and add an num value
        $weekToCalculate = "+ $days days";
        $dateTime = new DateTime();
        $dateTime->modify($weekToCalculate);
        $cwString = $dateTime->format('W');
        return $cwString;
    }

    public static function weekValueToCW($value)
    {
        $numbersOfDays = $value * 7;
        return self::calcCW($numbersOfDays);
    }

    public static function getWeekNameFromValue($value)
    {
        return self::WEEK_NAMES[$value];
    }
}
