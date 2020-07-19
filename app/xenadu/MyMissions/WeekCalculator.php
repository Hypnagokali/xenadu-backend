<?php
namespace Xenadu\MyMissions;

use DateTime;
use Exception;

class WeekCalculator
{

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
        /*
        switch ($value) {
            case 0:
                return self::calcCW(0);
                break;
            case 1:
                return self::calcCW(7);
                break;
            case 2:
                return self::calcCW(14);
                break;
            case 3:
                return self::calcCW(21);
                break;
            default:
                throw new Exception('Week Value was not 0, 1, 2 or 3');
        }*/
    }
}
