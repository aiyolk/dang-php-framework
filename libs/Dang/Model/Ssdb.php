<?php

namespace Dang\Model;

abstract class Ssdb
{
    protected static $_instance = null;

    protected function __construct()
    {
    }

    public static function instance()
    {
        if (static::$_instance === null) {
            static::$_instance = new static;
        }
        return static::$_instance;
    }
}
