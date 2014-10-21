<?php

function apps_simhashphp_autoloader($className){
    if (strpos($className, 'Leg') === 0) {
        require_once __DIR__.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
    }
}

spl_autoload_register('apps_simhashphp_autoloader');
