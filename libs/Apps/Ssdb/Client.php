<?php

namespace Apps\Ssdb;

class Client
{
    function __construct()
    {
        require 'SSDB.php';
    }

    public function connect($host, $port, $timeout_ms = 1000)
    {
        $ssdb = new \SimpleSSDB($host, $port, $timeout_ms);
        return $ssdb;
    }
}

?>
