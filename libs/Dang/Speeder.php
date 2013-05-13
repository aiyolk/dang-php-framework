<?php

/*
 * 速度计算器
 */

namespace Dang;

class Speeder
{
    private $_count = 0;

    public function start()
    {
        $this->_startTime = time();

        return $this->_startTime;
    }

    public function end()
    {
        $this->_endTime = time();

        return $this->_endTime;
    }

    public function count()
    {
        $this->_count++;

        return $this->_count;
    }

    public function getTotalTime()
    {
        $totalTime = $this->_endTime - $this->_endTime;

        return $totalTime;
    }

    public function getCount()
    {
        return $this->_count;
    }

    public function calculate()
    {
        $totalTime = $this->getTotalTime();
        if(!$totalTime){
            return "---/sec";
        }

        $count = $this->getCount();
        $speed = round($count/$totalTime);

        return $speed."/sec";
    }
}

?>
