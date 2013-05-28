<?php

/*
 * mysql配制文件
 */

$toUrl = array(
    'base' => '\Dang\Mvc\Route\Base',
);
$fromUrl = array(
    'base' => array(
        "/\/([a-z0-9-_]+)\/([a-z0-9-_]+)\/([a-z0-9-_]+)\/$/si",
        "/\/([a-z0-9-_]+)\/([a-z0-9-_]+)\/([a-z0-9-_]+)\/\?/si",
    ),
);
$return = array(
    'toUrl' => $toUrl,
    'fromUrl' => $fromUrl,
);

return $return;
