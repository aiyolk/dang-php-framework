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
            $port = "3306";
            if(isset($config->{$name}->port)){
                $port = $config->{$name}->port;
            }
            $dsn = "mysql:dbname=".$config->{$name}->dbname.";host=".$config->{$name}->host.";port=".$port;
            $db = new \Dang\Sql\SafePdo($dsn, $config->{$name}->user, $config->{$name}->passwd, array(
                \PDO::ATTR_PERSISTENT => true,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            ));
        }else{
            $db = new \Dang\Sql\Mysql();
            $db->connect($config->{$name}->host, $config->{$name}->user, $config->{$name}->passwd, $config->{$name}->dbname);
            $db->query('SET NAMES utf8');
        }

        $db->debug($dbdebug);

        return $db;
    }

    public static function mongo($name)
    {
        $config = \Dang\Quick::config("mongo");

        if($config->{$name}->replicaSet){
            $mongo = new \Mongo("mongodb://".$config->{$name}->host.":".$config->{$name}->port, array("replicaSet" => $config->{$name}->replicaSet));
        }else{
            $mongo = new \Mongo("mongodb://".$config->{$name}->host.":".$config->{$name}->port);
        }

        return $mongo;
    }

    public static function couchbase($bucket)
    {
        $config = \Dang\Quick::config("couchbase");

        $cb = new \Couchbase($config->{$bucket}, "", "", $bucket);

        return $cb;
    }

    public static function ttserver($name)
    {
        $config = \Dang\Quick::config("ttserver");
        $ttserver = new \TokyoTyrant($config->{$name}->host, $config->{$name}->port);

        return $ttserver;
    }

    public static function ssdb($name, $server = 'master')
    {
        $config = \Dang\Quick::config("ssdb");
        if(!isset($config->{$server}->{$name})){
            throw new \Exception("config/ssdb.php error! no key '$server/$name'");
        }
        $host = $config->{$server}->{$name}->host;
        $port = $config->{$server}->{$name}->port;
        $instance = \Apps\Quick::ssdb();
        $ssdb = $instance->connect($host, $port);
        return $ssdb;
    }

    public static function hbase($server)
    {
        $config = \Dang\Quick::config("hbase");
        if(!isset($config->{$server}->host) || !isset($config->{$server}->port)){
            throw new \Exception("config/hbase.php error! no key '$server'");
        }
        $host = $config->{$server}->host;
        $port = $config->{$server}->port;
        $hbase = \Apps\Quick::hbase();
        $client = $hbase->connect($host, $port);
        return $client;
    }

    public static function hbaseRest($server)
    {
        $config = \Dang\Quick::config("hbaseRest");
        if(!isset($config->{$server}->host) || !isset($config->{$server}->port)){
            throw new \Exception("config/hbaseRest.php error! no key '$server'");
        }
        $host = $config->{$server}->host;
        $port = $config->{$server}->port;
        $hbase = \Apps\Quick::hbaseRest();
        $client = $hbase->connect($host, $port);
        return $client;
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
