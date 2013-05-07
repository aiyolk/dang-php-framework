<?php

/**
 * 服务管理器，通过此方法调用各种实例(服务)
 * 对于没有单例化的实例非常方便，加入服务之后，可以在程序的任何地方调用
 *
 * @author wuqingcheng
 */

class Mvc_Service 
{
    private static $_instance;

    private $_services = array();
    
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
        return $this->_services[$name];
    }
    
}

?>
