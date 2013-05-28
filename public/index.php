<?php

/*
 * 入口文件
 */

//切换当前目录
chdir(dirname(dirname(__FILE__)));

//设置报错级别
error_reporting("2047");

//设置自动加载
include "./libs/Dang/Mvc/Autoloader.php";
$autoloader = new Dang_Mvc_Autoloader();
/*
 * 添加zend的加载路径
 * 若这样添加Zend：$autoloader->add("zend", "/opt/www/zend-framework-2");
 * 则zend的路径为：/opt/www/zend-framework-2/Zend/Config/Config.php
 */
$autoloader->add("zend", "/opt/www/zend-framework-2");
$autoloader->add("route", "./libs");
$autoloader->register();

//初始化入口
$enter = new Dang_Mvc_Enter();
//执行控制器里的代码
$enter->run();

?>
