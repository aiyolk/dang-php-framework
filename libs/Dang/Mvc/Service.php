<?php

/**
 * 服务管理器，通过此方法调用各种实例(服务)
 * 对于没有单例化的实例非常方便，加入服务之后，可以在程序的任何地方调用
 *
 * @author wuqingcheng
 */

class Dang_Mvc_Service
{
    private static $_instance;

    private $_services = array();
    private $_mapClass = array(
        'layoutModel' => 'Dang_Mvc_Model_HtmlModel',
    );
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

    public function add($name, $service)
    {
        $this->_services[$name] = $service;
    }

    public function get($name)
    {
        if(!array_key_exists($name, $this->_mapClass)){
            exit("Service '$name' not found!");
        }

        if(!isset($this->_services[$name])){
            $className = $this->_mapClass[$name];
            $this->_services[$name] = new $className();
        }

        return $this->_services[$name];
    }

}

?>
