<?php

class Dang_Mvc_View_Helper_TotalTime
{
    private $_standalone;

    public function _invoke()
    {
        $speeder = Dang_Mvc_ServiceManager::instance()->get("speeder");
        return $speeder->getTotalTime();
    }

}
