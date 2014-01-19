<?php

namespace Dang\Model;

abstract class Memcached
{
    protected static $_instance = array();

    protected $_dbdebug;
    protected $memcached;

    public function __construct()
    {
        $this->memcached = new \Dang\Dmemcached();
        
        $this->_dbdebug = \Dang_Mvc_Request::instance()->getParam("dbdebug", 0);
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

