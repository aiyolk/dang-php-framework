<?php

namespace Apps\Nongli;

class Client
{
    function __construct()
    {
        require 'LunarSolarConverter.class.php';
    }

    public function solarToLunar($date)
    {
        list($year, $month, $day) = explode("-", $date);
        $solar = new \Solar();
        $solar->solarYear = $year;
        $solar->solarMonth = $month;
        $solar->solarDay = $day;
        $lunar = \LunarSolarConverter::SolarToLunar($solar);
        
        return $lunar;
    }
}
