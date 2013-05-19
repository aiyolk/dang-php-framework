<?php

/**
 *
 * @author: wuqingcheng
 * @date: 2013.04.09
 */
abstract class Controller_Cli_Abstract extends Dang_Mvc_AbstractController
{
    //构造函数
    function __construct()
    {
        parent::__construct();

    }

    public function preDispatch()
    {
        parent::preDispatch();

        $argv = array();
        if(isset($_SERVER['argv'])){
            $argv = $_SERVER['argv'];
        }

        if(!$argv){
            exit("This script just run on console.\n");
        }

        $string = join(" ", $argv);
        exec("ps aux|grep '".$string."'$", $output, $return_var);
        if(count($output)>1){
            exit("This script is running! so exit.\n");
        }

    }

}

?>