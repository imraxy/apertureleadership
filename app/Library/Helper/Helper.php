<?php

// This class file to define all general functions
namespace App\Library\Helper;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str; 
use Carbon\Carbon;
use App\User;

/**
* Helper Class
*/
class Helper
{

    /************ Make Database date readable ************/
    public static function date_ago($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
    }

    /*********** Date format IN d/m/Y ***************/
    public static function dateformatDmy($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    /*********** Date format string IN M d, Y ***************/
    public static function dateformatmdy($date)
    {
        return Carbon::parse($date)->format('M d, Y');
    }

    /*********** Date format IN d/m/Y ***************/
    public static function date_string($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    /*********** Date format ***************/
    public static function dateFormat($date)
    {
        return Carbon::parse($date)->format('M-d-Y');
    }

    /*********** Date format (june 09,2018) ***************/
    public static function dateFormatMdYs($date)
    {
        return Carbon::parse($date)->format('M d,Y');
    }

    /*********** Date format ***************/
    public static function timeFormat($time)
    {
        return Carbon::parse($time)->format('h:i:s A');
    }

    /*********** Month Date format ***************/
    public static function dateFormatMonth($date)
    {
        return Carbon::parse($date)->format('M');
    }

    /*********** Date format ***************/
    public static function dateformatDate($date)
    {
        return Carbon::parse($date)->format('d');
    }


    /*********** Week format ***************/
    public static function weekFormat($date)
    {
        return Carbon::parse($date)->format('l');
    }

    /*********** Time format ***************/
    public static function formatTime($time)
    {
        return Carbon::parse($time)->format('H:i');
    }

    /**
     * String Date
     */
    public static function dateToFormatted($date)
    {
        return Carbon::parse($date)->toFormattedDateString(); 
    }


    /*********** Created date format ***************/
    public static function createdformatDate($date)
    {
        return Carbon::parse($date)->format('H:i');
    }

}
