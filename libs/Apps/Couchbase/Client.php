<?php

namespace Apps\Couchbase;

class Client
{
    function __construct()
    {
    }

    public function connect($host, $bucket)
    {
        $cb = new \Couchbase($host, "Administrator", "doudang123", $bucket);
        return $cb;
    }
}

?>
