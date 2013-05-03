<?php

/*
 * 入口文件
 */

chdir(dirname(dirname(__FILE__)));
        
error_reporting("E_ALL");

include "./libs/Mvc/Autoloader.php";
//设置自动加载
$autoloader = new Mvc_Autoloader();
$autoloader->register();
        
//初始化入口
$enter = new Mvc_Enter();
//执行控制器里的代码
$enter->run();

?>
