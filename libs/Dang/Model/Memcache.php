<?php

namespace Dang\Model;

abstract class Memcache
{
    protected static $_instance = array();

    protected $_dbdebug;
    protected $memcache;

    public function __construct()
    {
        $this->memcache = new \Dang\Dmemcache();
        
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

