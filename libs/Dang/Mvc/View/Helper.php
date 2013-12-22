<?php

/**
 * 视图助手
 *
 */
class Dang_Mvc_View_Helper
{
    protected static $_instance;

    /*
     * 单例方法
     */
    public static function instance()
    {
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct()
    {
    }

    public function __call($method, $argv)
    {
        return Dang_Mvc_View_HelperManager::instance()->getHelper($method, $argv);
    }
}

?>
