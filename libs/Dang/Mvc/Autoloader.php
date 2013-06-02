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
    private $_path = array();
    private $_extension = array();

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
        $this->_path[strtolower($namespace)] = $path;
        return $this;

    }

    /*
     * 添加特定类的扩展名
     */
    public function ext($namespace, $ext)
    {
        $this->_extension[strtolower($namespace)] = $ext;
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
        $extension = "php";

        preg_match("/^[\\\]?([a-z]+)([_\\\])/si", $className, $m);
        if(!$m){
            header("HTTP/1.0 404 Not Found");
            throw new Exception($className." no namespace!");
        }
        $namespace = strtolower($m[1]);
        $separator = $m[2];

        //查看定义的namespace 和 path
        if(key_exists($namespace, $this->_path)){
            $path = $this->_path[$namespace];
        }

        //查看定义的namespace 和 entension
        if(key_exists($namespace, $this->_extension)){
            $extension = $this->_extension[$namespace];
        }

        $filename = realpath($path)."/". preg_replace('/[_\\\]/', DIRECTORY_SEPARATOR, $className) . '.'. $extension;
        if(!file_exists($filename)){
            header("HTTP/1.0 404 Not Found");
            throw new Exception("File: ".$filename." not found!");
        }

        return include $filename;
    }
}

?>
