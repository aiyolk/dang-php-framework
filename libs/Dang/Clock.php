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

class Clock
{
    protected static $_timer = array();

    public static function getOne($name)
    {
        if(!isset(self::$_timer[$name])){
            self::$_timer[$name] = new \Dang\Timer($name);
        }

        return self::$_timer[$name];
    }

    public static function getAll()
    {
        return self::$_timer;
    }
}

?>
