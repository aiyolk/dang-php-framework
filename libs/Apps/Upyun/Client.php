<?php

namespace Apps\Upyun;

class Client
{
    function __construct()
    {
        require 'Upyun.php';
    }

    public function connect($bucketname, $username, $password)
    {
        $upyun = new \Apps_UpYun($bucketname, $username, $password);
        return $upyun;
    }
}

?>
