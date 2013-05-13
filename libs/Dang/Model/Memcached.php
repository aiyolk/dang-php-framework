<?php

namespace Dang\Model;

abstract class Memcached
{
    protected static $_instance = array();

    protected $memcached;

    public function __construct()
    {
        $this->memcached = new \Dang\Dmemcached();
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

