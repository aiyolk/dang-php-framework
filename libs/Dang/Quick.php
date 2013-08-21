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

        $db = new \Dang\Sql\Mysql($name);

        $db->debug($dbdebug);

        return $db;
    }

    public static function mongo($name)
    {
        $config = \Dang\Quick::config("mongo");
        //\MongoPool::setSize(2000);
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

    public static function redis($server)
    {
        $config = \Dang\Quick::config("redis");
        if(!isset($config->{$server}->host) || !isset($config->{$server}->port)){
            throw new \Exception("config/redis.php error! no key '$server'");
        }
        $db = 0;
        if(isset($config->{$server}->db)){
            $db = $config->{$server}->db;
            $db = intval($db);
        }

        $redis = new \Redis();
        $redis->pconnect($config->{$server}->host, $config->{$server}->port, $config->{$server}->timeout);
        $redis->select($db);

        return $redis;
    }

    public static function cassandra($server)
    {
        $config = \Dang\Quick::config("cassandra");
        if(!isset($config->{$server})){
            throw new \Exception("config/cassandra.php error! no key '$server'");
        }
        $cassandra = \Apps\Quick::cassandra();
        $client = $cassandra->connect($config->{$server});
        return $client;
    }

    public static function riak($server)
    {
        $client = new \Basho\Riak\Riak('127.0.0.1', 8098);

        return $client;
    }

    public static function captcha()
    {
        $session = new \Zend\Session\Container('base');
        //$session->setExpirationSeconds(60);

        $config = \Dang\Quick::config("captcha");
        $captcha = new \Zend\Captcha\Image($config->toArray());
        $captcha->setSession($session);
        //$captcha->setKeepSession(true);

        return $captcha;
    }

    public static function phpcassa($server)
    {
        $config = \Dang\Quick::config("hbase");
        if(!isset($config->{$server}->host) || !isset($config->{$server}->port)){
            throw new \Exception("config/hbase.php error! no key '$server'");
        }
        $host = $config->{$server}->host;
        $port = $config->{$server}->port;
        $hbase = \Apps\Quick::phpcassa();
        $client = $hbase->connect($host, $port);
        return $client;
    }

    public static function config($name)
    {
        if (isset(self::$_config[$name])) {
            return self::$_config[$name];
        }

        include '/var/www/mychemy.com/config/'.$name.'.php';

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
