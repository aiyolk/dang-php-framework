<?php

/**
 * Autoloader 自动加载
 * 对目录层级没有限制
 * 支持下划线和路径(php>5.3)两种形式的类名
 * 默认的加载路径为 ./libs
 *
 * @author wuqingcheng
 * @create by 2013.04.03
 * @modify by 2013.05.14
 */

class Dang_Mvc_Autoloader
{
    private $_namespace = array();
    
    public function register()
    {
        spl_autoload_register(array($this, "load"));
        return $this;
        
    }

    /*
     * 根据namespace设置自动加载的路径
     * @param $namespace 命名空间
     * @path 路径
     */
    public function add($namespace, $path)
    {
        $this->_namespace[strtolower($namespace)] = $path;
        return $this;
        
    }
    
    /*
     * 根据命名空间加载相应的include path
     */
    private function load($className)
    {
        if (class_exists($className, false) || interface_exists($className, false)) {
            return false;
        }

        $path = "./libs/";

        preg_match("/^[\\\]?([a-z]+)([_\\\])/si", $className, $m);
        if(!$m){
            throw new Exception($className." no namespace!");
        }
        $namespace = strtolower($m[1]);
        $separator = $m[2];

        //查看定义的namespace 和 path
        if(key_exists($namespace, $this->_namespace)){
            $path = $this->_namespace[$namespace];
        }
        
        $filename = realpath($path)."/". preg_replace('/[_\\\]/', DIRECTORY_SEPARATOR, $className) . '.php';
        if(!file_exists($filename)){
            throw new Exception("File: ".$filename." not found!");
        }
        
        return include $filename;
    }
}

?>
