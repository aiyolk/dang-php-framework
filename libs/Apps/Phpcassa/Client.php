<?php

namespace Apps\Phpcassa;

class Client
{
    private $_client;

    function __construct()
    {
        $GLOBALS['THRIFT_ROOT'] = dirname(__FILE__)."/thrift";

        require_once $GLOBALS['THRIFT_ROOT'].'/packages/cassandra/Cassandra.php';
        require_once $GLOBALS['THRIFT_ROOT'].'/transport/TSocket.php';
        require_once $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php';
        require_once $GLOBALS['THRIFT_ROOT'].'/transport/TFramedTransport.php';
        require_once $GLOBALS['THRIFT_ROOT'].'/transport/TBufferedTransport.php';

        include_once(dirname(__FILE__) . '/phpcassa.php');
        include_once(dirname(__FILE__) . '/uuid.php');
    }

    public function connect($servers)
    {
        \CassandraConn::add_node('192.168.2.100', 9160);
    }

}

?>
