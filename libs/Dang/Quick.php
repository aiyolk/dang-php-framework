<?php

namespace Dang;

class Quick
{
    private static $_config = array();
    private static $_logger = array();

    /*
     * 创建数据库连接
     */
    public static function mysql($name)
    {
        $dbdebug = \Dang_Mvc_Request::instance()->getParamGet("dbdebug", 0);

        $config = \Dang\Quick::config("mysql");

        if($config->{$name}->tool == "Pdo"){
            $dsn = "mysql:dbname=".$config->{$name}->dbname.";host=".$config->{$name}->host;
            $db = new \Dang\Sql\SafePdo($dsn, $config->{$name}->user, $config->{$name}->passwd, array(\PDO::ATTR_PERSISTENT => true));
        }else{
            $db = new \Dang\Sql\Mysql();
            $db->connect($config->{$name}->host, $config->{$name}->user, $config->{$name}->passwd, $config->{$name}->dbname);
        }

        $db->debug($dbdebug);

        return $db;
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
