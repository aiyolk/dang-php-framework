<?php

namespace Dang\Sql;

class Mysql
{
    private $_newLink;
    private $_dblink;
    private $_debug;

    //－－－－－－－－-
    //php5构造函数
    //－－－－－－－－-
    function __construct($newLink = false)
    {
        $this->_newLink = $newLink;
    }

    public function connect($dbhost, $dbuser, $dbpw, $dbname)
    {
        if(!$this->_dblink = mysql_connect($dbhost, $dbuser, $dbpw, $this->_newLink)) {
            $this->halt('Unable to connect the MySQL server.');
        }

        mysql_query("SET names utf8", $this->_dblink);
        mysql_select_db($dbname, $this->_dblink);

        return $this;
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC)
    {
        return mysql_fetch_array($query, $result_type);
    }

    function query($sql, $method = '')
    {
        \Zend\Debug\Debug::dump($sql, "sql: ", $this->_debug);

        $query = mysql_query($sql, $this->_dblink);
        if(!$query) {
            $this->halt('MySQL Query Error.');
        }

        return $query;
    }

    function executeInsert($table, $data, $action = 'INSERT')
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

        return $this->query($query);
    }

    function executeUpdate($table, $data, $where = '')
    {
        reset($data);

        $query = 'UPDATE `' . $table . '` SET ';
        $space='';
        foreach($data as $key=>$val)
        {
            $query .= $space.$key . "= '" . $val. "'";
            $space=', ';
        }
        $query .=' WHERE ' . $where.' ';

        return $this->query($query,'ub');
    }

    function GetOne($sql)
    {
        $query = $this->query($sql);
        $result = $this->result($query, 0);
        return $result;
    }

    function GetRow($sql)
    {
        $query = $this->query($sql);
        $result = $this->fetch_array($query);
        return $result;
    }

    function GetAll($sql)
    {
        $query = $this->query($sql);
        $ret = array();
        while($result = $this->fetch_array($query))
        {
            $ret[] = $result;
        }
        return $ret;
    }

    function GetCount($sql)
    {
        $query = $this->query($sql);
        $ret = reset($this->fetch_array($query));
        return $ret;
    }

    function GetNumRows($sql)
    {
        $query = $this->query($sql);
        return $this->num_rows($query);
    }

    function affected_rows()
    {
        return mysql_affected_rows($this->_dblink);
    }

    function error()
    {
        return mysql_error();
    }

    function errno()
    {
        return mysql_errno();
    }

    function result($query, $row)
    {
        return @mysql_result($query, $row);
    }

    function num_rows($query)
    {
        return mysql_num_rows($query);
    }

    function num_fields($query)
    {
        return mysql_num_fields($query);
    }

    function field_name($query)
    {
        return mysql_field_name($query);
    }

    function free_result($query)
    {
        return mysql_free_result($query);
    }

    function insert_id()
    {
        return mysql_insert_id($this->_dblink);
    }

    function fetch_row($query)
    {
        return mysql_fetch_row($query);
    }

    function close()
    {
        return mysql_close($this->_dblink);
    }

    function version()
    {
        return mysql_get_server_info();
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
