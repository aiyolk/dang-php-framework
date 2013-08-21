<?php

namespace Dang\Sql;

class Mysql extends MysqlPdo
{
    function __construct($dbname)
    {
        $config = \Dang\Quick::config("mysql");

        $port = "3306";
        if(isset($config->{$dbname}->port)){
            $port = $config->{$dbname}->port;
        }

        parent::__construct($config->{$dbname}->dbname, $config->{$dbname}->host, $port, $config->{$dbname}->user, $config->{$dbname}->passwd);
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
