<?php

class Dang_Mvc_View_Helper 
{
    public function __call($method, $argv)
    {
        return Dang_Mvc_View_HelperManager::instance()->getHelper($method, $argv);
    }
}

?>
