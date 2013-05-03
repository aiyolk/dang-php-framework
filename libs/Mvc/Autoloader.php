<?php

/**
 * Autoloader 自动 加载 类，主要为了防止与公共 类中的__autoload()函数冲突
 * 且对目录层级没有限制
 * 
 * @author wuqingcheng
 * @create by 2013.04.03
 */

class Mvc_Autoloader 
{
    private $_path;
    
    public function register() 
    {
        spl_autoload_register(array($this, "load"));
    }
    
    /*
     * 根据命名空间加载相应的include path
     */
    private function load($className) 
    {
        if (class_exists($className, false) || interface_exists($className, false)) {
            return false;
        }
        
        //命名空间列表
        $pathArray = array(
            'www' => "./controller/Www",
            'mvc' => "./libs/Mvc",
            'dang' => "./libs/Dang",
        );
        
        preg_match("/^([a-z]+)_/si", $className, $m);
        if(!$m){
            exit($className." has no namespace");
        }
        $namespace = $m[1];
        $path = $pathArray[strtolower($namespace)];
        
        $filename = realpath($path)."/".str_replace('_', '/', preg_replace("/^([a-z]+)_/si", "", $className)) . '.php';

        return include $filename;
    }
}

?>
