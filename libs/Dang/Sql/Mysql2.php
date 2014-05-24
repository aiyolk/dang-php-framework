<?php

namespace Dang\Sql;

class Mysql2 extends MysqlPdo
{
    function __construct($dbname)
    {
        $config = \Dang\Quick::config("mysql2");

        $port = "3306";
        if(isset($config->{$dbname}->port)){
            $port = $config->{$dbname}->port;
        }

        parent::__construct($dbname, $config->{$dbname}->host, $port, $config->{$dbname}->user, $config->{$dbname}->passwd);
    }
}

?>
