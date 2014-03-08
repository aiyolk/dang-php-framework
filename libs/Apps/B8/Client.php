<?php

namespace Apps\B8;

class Client
{
    function __construct()
    {
        require 'b8/b8.php';
    }

    public function init($config = array(), $config_storage = array(), $config_lexer = array(), $config_degenerator = array())
    {
        $b8 = new \b8($config, $config_storage, $config_lexer, $config_degenerator);
        return $b8;
    }
}

?>
