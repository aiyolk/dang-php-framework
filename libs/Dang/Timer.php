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

class Timer extends Build
{
    private $_name;
    private $_start = 0;
    private $_end = 0;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function start()
    {
        $this->_start = microtime(true);

        return $this->_start;
    }

    public function getStart()
    {
        return $this->_start;
    }

    public function setStart($time)
    {
        $this->_start = $time;

        return $this;
    }

    public function end()
    {
        $this->_end = microtime(true);

        return $this->_end;
    }

    public function getEnd()
    {
        return $this->_end;
    }

    public function setEnd($time)
    {
        $this->_end = $time;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getTotal()
    {
        $totalTime = $this->_end - $this->_start;
        $totalTime = round($totalTime, 4);

        return $totalTime;
    }

}

?>
