<?php

/*
 * mysql配制文件
 */

$toUrl = array(
    'base' => '\Dang\Mvc\Route\Base',
    'product' => '\Route\Product',
);
$fromUrl = array(
    'base' => array(
        "/^\/([a-z0-9-_]+)\/([a-z0-9-_]+)\/([a-z0-9-_]+)[\/]?\??/si",
    ),
    'product' => array(
        "/\/product\/([0-9]+).html/si",
    ),
);
$return = array(
    'defaultRoute' => "base", //设置默认使用的路由
    'toUrl' => $toUrl,
    'fromUrl' => $fromUrl,
);

return $return;
