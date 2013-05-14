<?php

namespace Dang;

class Quick
{
    private static $_mysql = array();
    private static $_config = array();
    private static $_logger = array();

    public static function mysql($name)
    {
        if (isset(self::$_mysql[$name])) {
            return self::$_mysql[$name];
        }

        $dbdebug = \Dang_Mvc_Request::instance()->getParamGet("dbdebug", 0);

        $config = \Dang\Quick::config("mysql");

        /*
         * 初始化mysql实例，要求新建mysql连接的resource id
         */
        $db = new \Dang\Sql\Mysql(true);
        $db->connect($config->{$name}->host, $config->{$name}->user, $config->{$name}->passwd, $config->{$name}->dbname);

        $db->debug($dbdebug);

        self::$_mysql[$name] = $db;

        return self::$_mysql[$name];
    }

    public static function config($name)
    {
        if (isset(self::$_config[$name])) {
            return self::$_config[$name];
        }

        $config = \Zend\Config\Factory::fromFile('./config/'.$name.'.php', true);
        if($config){
            self::$_config[$name] = $config;
        }

        return self::$_config[$name];
    }

    public static function logger($name)
    {
        if (isset(self::$_logger[$name])) {
            return self::$_logger[$name];
        }

        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream('/tmp/'.$name.'.log');
        $logger->addWriter($writer);

        self::$_logger[$name] = $logger;

        return self::$_logger[$name];
    }
}

?>
