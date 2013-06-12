<?php

namespace Apps\Hbase;

class Client
{
    private $_client;
    private $_transport;

    function __construct()
    {
        $GLOBALS['THRIFT_ROOT'] = dirname(__FILE__);

        require_once( $GLOBALS['THRIFT_ROOT'] . '/Thrift.php' );
        require_once( $GLOBALS['THRIFT_ROOT'] . '/transport/TSocket.php' );
        require_once( $GLOBALS['THRIFT_ROOT'] . '/transport/TBufferedTransport.php' );
        require_once( $GLOBALS['THRIFT_ROOT'] . '/protocol/TBinaryProtocol.php' );
        require_once( $GLOBALS['THRIFT_ROOT'] . '/packages/Hbase/Hbase.php' );
    }

    public function connect($host, $port)
    {
        $socket = new \TSocket($host, $port);

        $socket->setSendTimeout(10000); // Ten seconds (too long for production, but this is just a demo ;)
        $socket->setRecvTimeout(20000); // Twenty seconds
        $transport = new \TBufferedTransport($socket);
        $protocol = new \TBinaryProtocol($transport);
        $client = new \HbaseClient($protocol);

        $this->_transport = $transport;
        $this->_client = $client;

        return $this;
    }

    public function getClient()
    {
        return $this->_client;
    }

    public function open()
    {
        $this->_transport->open();

        return $this;
    }

    public function close()
    {
        $this->_transport->close();

        return $this;
    }
}

?>
