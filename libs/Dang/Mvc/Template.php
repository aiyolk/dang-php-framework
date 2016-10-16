<?php

/*
 * 模板暂存器
 *
 * @author wuqingcheng
 * @date 2013.04.18
 */

class Dang_Mvc_Template
{
    protected static $_instance = null;

    protected $_path;
    protected $_layout;
    protected $_module;
    protected $_device;
    protected $_controller;
    protected $_action;
    protected $_extension;
    protected $_partial;

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

    public function setPath($path)
    {
        $this->_path = (string)$path;
        return $this;
    }

    public function getPath()
    {
        if(isset($this->_path)){
            return $this->_path;
        }

        $tplDir = "./tpl";
        $config = \Dang\Quick::config("base");
        if(isset($config->tplDir)){
            $tplDir = $config->tplDir;
        }

        $this->_path = (string)$tplDir;
        return $this->_path;
    }

    public function setLayout($name)
    {
        $this->_layout = $name;
        return $this;
    }

    public function getLayout()
    {
        if(isset($this->_layout)){
            return $this->_layout;
        }

        $this->_layout = "Layout";
        return $this->_layout;
    }

    public function setPartial($name)
    {
        $this->_partial = $name;
        return $this;
    }

    public function getPartial()
    {
        return $this->_partial;
    }

    public function setModule($name)
    {
        $this->_module = $name;
        return $this;
    }

    public function getModule()
    {
        if(!isset($this->_module)){
            $module = Dang_Mvc_Param::instance()->getModule();
            $this->_module = $module;
        }

        return $this->_module;
    }

    public function setDevice($name)
    {
        $this->_device = ucfirst($name);
        return $this;
    }

    public function getDevice()
    {
        if(isset($this->_device)){
            return $this->_device;
        }

        $device = Dang_Mvc_Param::instance()->getDevice();
        $this->_device = ucfirst($device);
        return $this->_device;
    }

    public function getDefaultDevice()
    {
        $defaultDevice = "pc";
        $config = \Dang\Quick::config("base");
        if(isset($config->defaultDevice)){
            $defaultDevice = $config->defaultDevice;
        }
        $defaultDevice = ucfirst($defaultDevice);
        return $defaultDevice;
    }

    public function getController()
    {
        if(!isset($this->_controller)){
            $controller = Dang_Mvc_Param::instance()->getController();
            $this->_controller = $controller;
        }

        return $this->_controller;
    }

    public function setController($name)
    {
        $this->_controller = $name;
        return $this;
    }

    public function setAction($name)
    {
        $this->_action = $name;
        return $this;
    }

    public function getAction()
    {
        if(!isset($this->_action)){
            $action = Dang_Mvc_Param::instance()->getAction();
            $this->_action = $action;
        }

        return $this->_action;
    }

    public function setExtension($ext)
    {
        $this->_extension = (string)$ext;
        return $this;
    }

    public function getExtension()
    {
        if($this->_extension){
            return $this->_extension;
        }

        $this->_extension = "phtml";

        return $this->_extension;
    }

    public function getPartialFilename()
    {
        if(substr($this->getPartial(), 0, 1) == "/"){
            $filename = $this->getPartial(). ".".$this->getExtension();
        }else{
            $filename = (string)$this->getPath(). "/".$this->getDevice()."/".$this->getPartial(). ".".$this->getExtension();
        }
        if(file_exists($filename)){
            return $filename;
        }

        //如果没有找到适配的驱动模板，则使用默认模板
        $defaultDevice = $this->getDefaultDevice();
        if($defaultDevice == $this->getDevice()){
            throw new Exception($filename." not found!");
        }

        $filename = (string)$this->getPath(). "/".$defaultDevice."/".$this->getPartial(). ".".$this->getExtension();
        if(!file_exists($filename)){
            throw new Exception("Partial file: ".$filename."(under default device) not found!");
        }

        return $filename;
    }

    public function getActionFilename()
    {
        $filename = (string)$this->getPath(). "/".$this->getDevice()."/".$this->getModule()."/".$this->getController()."/".$this->getAction(). ".".$this->getExtension();
        if(file_exists($filename)){
            return $filename;
        }

        //如果没有找到适配的驱动模板，则使用默认模板
        $defaultDevice = $this->getDefaultDevice();
        if($defaultDevice == $this->getDevice()){
            throw new Exception($filename." not found!");
        }

        //模板一致性（加入下面的这句，可以使action和layout及partial调用同一个驱动器下的模板）
        $config = \Dang\Quick::config("base");
        if(isset($config->tplConsistency) && $config->tplConsistency == "true"){
            $this->setDevice($defaultDevice);
        }

        $filename = (string)$this->getPath(). "/".$defaultDevice."/".$this->getModule()."/".$this->getController()."/".$this->getAction(). ".".$this->getExtension();
        if(!file_exists($filename)){
            throw new Exception("Action file: ".$filename."(under default device) not found!");
        }

        return $filename;
    }

    public function getLayoutFilename()
    {
        $filename = (string)$this->getPath(). "/".$this->getDevice()."/".$this->getModule()."/".$this->getLayout(). ".".$this->getExtension();
        if(file_exists($filename)){
            return $filename;
        }

        //当前驱动根目录下的模板layout文件
        $filename = (string)$this->getPath(). "/".$this->getDevice()."/".$this->getLayout(). ".".$this->getExtension();
        if(file_exists($filename)){
            return $filename;
        }

        //如果没有找到适配的驱动模板，则使用默认模板
        $defaultDevice = $this->getDefaultDevice();
        if($defaultDevice == $this->getDevice()){
            throw new Exception($filename." not found!");
        }

        $filename = (string)$this->getPath(). "/".$defaultDevice."/".$this->getModule()."/".$this->getLayout(). ".".$this->getExtension();
        if(!file_exists($filename)){
            throw new Exception("Layout file: ".$filename."(under default device) not found!");
        }

        return $filename;
    }
}
