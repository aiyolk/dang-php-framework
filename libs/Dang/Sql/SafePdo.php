<?php

namespace Dang\Sql;

Class SafePdo extends \PDO
{
	private $_debug = false;

    public static function exception_handler($exception)
    {
        throw new \Exception('Uncaught exception: '. $exception->getMessage());
    }

    /*
     * 如果应用程序不在 PDO 构造函数中捕获异常，zend 引擎采取的默认动作是结束脚本并显示一个回溯跟踪，此回溯跟踪可能泄漏完整的数据库连接细节，包括用户名和密码。因此有责任去显式（通过 catch 语句）或隐式（通过 set_exception_handler() ）地捕获异常。
     *
     * 参考：http://php.net/manual/zh/pdo.connections.php
     */
    public function __construct($dsn, $username='', $password='', $driver_options=array())
    {
        set_exception_handler(array(__CLASS__, 'exception_handler'));

        parent::__construct($dsn, $username, $password, $driver_options);

        restore_exception_handler();
    }

	function query($sql)
	{
        \Zend\Debug\Debug::dump($sql, "sql: ", $this->_debug);

        $PDOStatement = parent::query($sql);

        return $PDOStatement;
	}

    function exec($sql)
	{
        \Zend\Debug\Debug::dump($sql, "sql: ", $this->_debug);

        $result = parent::exec($sql);

        return $result;
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

        $result = $this->exec($query);

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

        $result = $this->exec($query);

        return $result;
    }

	function GetOne($sql)
	{
		$PDOStatement = $this->query($sql);
        $result = $PDOStatement->fetchColumn();

		return $result;
	}

	function GetRow($sql)
	{
		$PDOStatement = $this->query($sql);
		$result = $PDOStatement->fetch(\PDO::FETCH_ASSOC);

		return $result;
	}

	function getAll($sql)
	{
		$PDOStatement = $this->query($sql);
		$result = $PDOStatement->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}

    function insert_id()
    {
        print $this->lastInsertId();
    }

    function debug($debug)
	{
        $this->_debug = $debug;
    }

    function close()
	{
    }
}

?>
