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
        'totalTime'           => 'Dang_Mvc_View_Helper_TotalTime',
        'clock'               => 'Dang_Mvc_View_Helper_Clock',
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

    public function addInvoke($name, $class)
    {
        $invokes = array();
        $invokes[$name] = $class;
        $this->addInvokes($invokes);

        return $this;
    }

    public function addInvokes($invokes)
    {
        $this->_invokableClasses = array_merge($this->_invokableClasses, $invokes);

        return $this;
    }

    public function getInvoke($name)
    {
        if(!array_key_exists($name, $this->_invokableClasses)){
            throw new Exception("View helper '$name' not found!");
        }

        if(!isset($this->_services[$name])){
            $className = $this->_invokableClasses[$name];
            $class = new $className();
            $this->_services[$name] = array($class, "_invoke");
        }

        return $this->_services[$name];
    }

    /*
     * 只方法只能在 __call 方法里使用, $argv 是__call捕获的参数
     */
    public function getHelper($name, $argv)
    {
        $viewHelper = $this->getInvoke($name);
        return call_user_func_array($viewHelper, $argv);
    }

}
