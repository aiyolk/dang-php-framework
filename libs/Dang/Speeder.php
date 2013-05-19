<?php

/*
 * 速度计算器
 *
 * @author wuqingcheng
 * @email wqc200@gmail.com
 * @create 2013.05.15
 * @modify
 */

namespace Dang;

class Speeder
{
    private $_startTime = 0;
    private $_endTime = 0;
    private $_count = 0;

    public function reset()
    {
        $this->_startTime = 0;
        $this->_endTime = 0;
        $this->_count = 0;
    }

    public function start()
    {
        $this->_startTime = microtime(true);

        return $this->_startTime;
    }

    public function setStartTime($time)
    {
        $this->_startTime = $time;

        return $this;
    }

    public function end()
    {
        $this->_endTime = microtime(true);

        return $this->_endTime;
    }

    public function setEndTime($time)
    {
        $this->_endTime = $time;

        return $this;
    }

    public function count()
    {
        $this->_count++;

        return $this->_count;
    }

    public function setCount($count)
    {
        $this->_count = $count;

        return $this;
    }

    public function getCount()
    {
        return $this->_count;
    }

    public function getTotalTime()
    {
        $totalTime = $this->_endTime - $this->_startTime;
        $totalTime = round($totalTime, 4);

        return $totalTime;
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
