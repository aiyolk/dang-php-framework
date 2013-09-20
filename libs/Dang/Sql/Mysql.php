<?php

namespace Dang\Sql;

class Mysql extends MysqlPdo
{
    function __construct($dbname, $server = "slaver")
    {
        $config = \Dang\Quick::config("mysql");

        $port = "3306";
        if(isset($config->{$server}->port)){
            $port = $config->{$server}->port;
        }

        parent::__construct($dbname, $config->{$server}->host, $port, $config->{$server}->user, $config->{$server}->passwd);
    }

    function insert_id()
    {
        return $this->lastInsertId();
    }

    function halt($message)
    {
        $dberror = $this->error();
        $dberrno = $this->errno();

        if($dberrno == 1114){
            echo(" Mysql wrining ({$dberrno}): too many connectting.");

        }else{
            echo(" Mysql error ".$dberrno.":".$dberror);

        }

        exit;
    }
}

?>
