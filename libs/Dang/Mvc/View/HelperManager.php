<?php


class Dang_Mvc_View_HelperManager
{
    protected $_invokableClasses = array(
        'url'                 => 'Dang_Mvc_View_Helper_Url',
        'headMeta'            => 'Dang_Mvc_View_Helper_HeadMeta',
        'headTitle'           => 'Dang_Mvc_View_Helper_HeadTitle',
        'layout'              => 'Dang_Mvc_View_Helper_Layout',
        'paginationControl'   => 'Dang_Mvc_View_Helper_PaginationControl',
        'partial'             => 'Dang_Mvc_View_Helper_Partial',
        'serverUrl'           => 'Dang_Mvc_View_Helper_ServerUrl',
    );
    
    protected static $_instance;

    protected $_services = array();

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
            exit("View helper '$name' not found!");
        }

        if(!isset($this->_services[$name])){
            $className = $this->_invokableClasses[$name];
            $class = new $className();
            $this->_services[$name] = array($class, "_invoke");
        }

        return $this->_services[$name];
    }

}
