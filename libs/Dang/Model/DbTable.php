<?php

namespace Dang\Model;

abstract class DbTable
{
    protected static $_instance = array();

    protected $_server;

    public function __construct($server = "slaver") {
        $this->_server = $server;
    }

    public static function instance($server = "slaver")
    {
        $_className = get_called_class();
        if (!isset(self::$_instance[$_className][$server])) {
            self::$_instance[$_className][$server] = new $_className($server);
        }

        return self::$_instance[$_className][$server];
    }
}