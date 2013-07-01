<?php

namespace Dang\Sql;

class Mysql
{
    private $_db;
    private $_debug;

    function __construct($dbname)
    {
        $config = \Dang\Quick::config("mysql");

        $port = "3306";
        if(isset($config->{$dbname}->port)){
            $port = $config->{$dbname}->port;
        }
        $dsn = "mysql:dbname=".$config->{$dbname}->dbname.";host=".$config->{$dbname}->host.";port=".$port;
        $db = new \Dang\Sql\SafePdo($dsn, $config->{$dbname}->user, $config->{$dbname}->passwd, array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        ));

        $this->_db = $db;
    }

    function query($sql)
	{
        \Zend\Debug\Debug::dump($sql, "sql: ", $this->_debug);

        $PDOStatement = $this->_db->query($sql);

        return $PDOStatement;
	}

    function prepare($sql)
	{
        \Zend\Debug\Debug::dump($sql, "sql: ", $this->_debug);

        return $this->_db->prepare($sql);
	}

    function lastInsertId()
	{
        return $this->_db->lastInsertId();
    }

    function executeInsert($table, $data, $action = "INSERT")
	{
		reset($data);

        $space = $query_1 = $query_2 = '';
        foreach($data as $key=>$val)
        {
            $query_1 .= $space.$key;
            $query_2 .= $space."'".$val."'";
            $space=', ';
        }
        $query = $action.' INTO `' . $table . '` ('.$query_1.') VALUES ('.$query_2.')';

        $sth = $this->prepare($query);
        $result = $sth->execute();
        $sth->closeCursor();

        return $result;
    }

    function executeUpdate($table, $data, $where = '')
	{
        $query = 'UPDATE `' . $table . '` SET ';
        $space='';
        foreach($data as $key=>$val)
        {
            $query .= $space.$key . "= '" . $val. "'";
            $space=', ';
        }
        $query .=' WHERE ' . $where.' ';

        #$result = $this->exec($query);
        $sth = $this->prepare($query);
        $result = $sth->execute();
        $sth->closeCursor();

        return $result;
    }

	function getOne($sql)
	{
        $sth = $this->prepare($sql);
        $sth->execute();
        $result = $sth->fetchColumn();

		return $result;
	}

	function getRow($sql)
	{
		$PDOStatement = $this->query($sql);
		$result = $PDOStatement->fetch(\PDO::FETCH_ASSOC);

		return $result;
	}

    function getAll($sql)
	{
        $sth = $this->prepare($sql);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $sth->execute();
        $result = $sth->fetchAll();

		return $result;
	}

    function getInsertId()
    {
        return $this->lastInsertId();
    }

    function insert_id()
    {
        return $this->lastInsertId();
    }


    function close()
    {
        $this->_db = null;
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

    function debug($debug)
    {
        $this->_debug = $debug;
    }
}

?>
