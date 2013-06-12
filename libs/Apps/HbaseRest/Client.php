<?php

namespace Apps\HbaseRest;

class Client
{
    function __construct()
    {
        require 'hbase_rest.php';
    }

    public function connect($host, $port)
    {
        $client = new \hbase_rest($host, $port);
        return $client;
    }
}

?>
