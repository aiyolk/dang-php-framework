<?php

class Dang_Mvc_View_Helper_Clock
{
    public function _invoke()
    {
        return $all = \Dang\Clock::getAll();
    }
}
