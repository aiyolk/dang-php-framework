<?php

namespace Dang\Model;

abstract class DbTable
{
    protected static $_instance = array();

    public function __construct()
    {

    }

    public static function instance()
    {
        $_className = get_called_class();
        if (!isset(self::$_instance[$_className])) {
            self::$_instance[$_className] = new $_className();
        }

        return self::$_instance[$_className];
    }
}