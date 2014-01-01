<?php

namespace Dang\Sql;

class AliyunRds
{
    protected static $_instance = null;

    private $_dbs = array();

    /*
     * 单例模式入口
     */
    public static function instance()
    {
        if(self::$_instance == null){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    function __construct()
    {
    }

    public function open($dbname)
    {
        if(isset($this->_dbs[$dbname])){
            return $this->_dbs[$dbname];
        }

        $dbdebug = \Dang_Mvc_Request::instance()->getParamGet("dbdebug", 0);

        $config = \Dang\Quick::config("aliyunRds");

        $host = $config->{$dbname}->host;
        $username = $config->{$dbname}->username;
        $password = $config->{$dbname}->password;
        $port = "3306";
        if(isset($config->{$dbname}->port)){
            $port = $config->{$dbname}->port;
        }

        $db = new \Dang\Sql\MysqlPdo($dbname, $host, $port, $username, $password);
        $db->debug($dbdebug);

        $this->_dbs[$dbname] = $db;

        return $this->_dbs[$dbname];
    }

    public function close($dbname = null)
    {
        $aliyunRds = $this->_dbs;
        foreach ($aliyunRds as $_dbname => $db) {
            unset($this->_dbs[$_dbname]);
            print_r($this->_dbs);
            if($dbname == $_dbname){
                $db->close();
                break;
            }else{
                $db->close();
            }
        }

        print_r($this->_dbs);

        return true;
    }
}

?>
