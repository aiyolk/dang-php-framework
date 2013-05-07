<?php

/*
 * 模板暂存器
 * 
 * @author wuqingcheng
 * @date 2013.04.18
 */

class Mvc_Template
{
    protected static $_instance = null; 
    
    protected $_layoutModel;
    protected $_layout;
    protected $_module;
    protected $_controller;
    protected $_action;
    
    /*
     * 单例模式入口
     */
    public static function instance()
    {
        if(self::$_instance == null){
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function getLayout()
    {
        if(!isset($this->_layout)){
            $this->_layout = "Layout";
        }
        
        return $this->_layout;
    }

    public function setLayout($name)
    {
        $this->_layout = $name;
        return $this;
    }
    
    public function getModule()
    {
        if(!isset($this->_module)){
            $module = Mvc_Param::instance()->getModule();
            $this->_module = $module;
        }
        
        return $this->_module;
    }

    public function setModule($name)
    {
        $this->_module = $name;
        return $this;
    }
    public function getController()
    {
        if(!isset($this->_controller)){
            $controller = Mvc_Param::instance()->getController();
            $this->_controller = $controller;
        }
        
        return $this->_controller;
    }

    public function setController($name)
    {
        $this->_controller = $name;
        return $this;
    }
    
    public function getAction()
    {
        if(!isset($this->_action)){
            $action = Mvc_Param::instance()->getAction();
            $this->_action = $action;
        }
        
        return $this->_action;
    }

    public function setAction($name)
    {
        $this->_action = $name;
        return $this;
    }
}
