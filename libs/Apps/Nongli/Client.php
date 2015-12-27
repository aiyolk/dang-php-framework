<?php

namespace Apps\Nongli;

class Client
{
    function __construct()
    {
        require 'LunarSolarConverter.class.php';
    }

    public function gongliDateToLunar($date)
    {
        list($year, $month, $day) = explode("-", $date);
        $solar = new \Solar();
        $solar->solarYear = $year;
        $solar->solarMonth = $month;
        $solar->solarDay = $day;
        $lunar = \LunarSolarConverter::SolarToLunar($solar);
        
        return $lunar;
    }
    
    public function nongliDateToSolar($date)
    {
        list($year, $month, $day) = explode("-", $date);
        $lunar = new \Lunar();
        $lunar->lunarYear = $year;
        $lunar->lunarMonth = $month;
        $lunar->lunarDay = $day;
        $solar = \LunarSolarConverter::LunarToSolar($lunar);
    
        return $solar;
    }
}
