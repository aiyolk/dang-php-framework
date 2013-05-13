<?php

namespace Model\Mongodb;

abstract class AbstractMongodb
{
    protected static $_instance = array();

    protected $mongo;

    public function __construct()
    {
        $config = \Dang\Quick::config("mongodb");
        if(APPLICATION_ENV == "development"){
            $this->mongo = new \Mongo("mongodb://".$config->host.":".$config->port."");
        }else{
            $this->mongo = new \Mongo("mongodb://".$config->host.":".$config->port."", array("replicaSet" => "myset"));
        }
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