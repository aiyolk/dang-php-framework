<?php

/**
 * 服务管理器，通过此方法调用各种实例(服务)
 * 对于没有单例化的实例非常方便，加入服务之后，可以在程序的任何地方调用
 *
 * @author wuqingcheng
 */

class Dang_Mvc_ServiceManager
{
    public static $_instance;

    protected $_services = array();
    protected $_invokableClasses = array(
        'layoutModel' => 'Dang_Mvc_View_Model_HtmlModel', //布局模型
        'speeder' => '\Dang\Speeder', //全局速度计算器
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

    public function get($name)
    {
        if(!array_key_exists($name, $this->_invokableClasses)){
            exit("Service '$name' not found!");
        }

        if(!isset($this->_services[$name])){
            $className = $this->_invokableClasses[$name];
            $this->_services[$name] = new $className();
        }

        return $this->_services[$name];
    }

}

?>
