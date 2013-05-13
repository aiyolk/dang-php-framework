<?php

/**
 * Autoloader 自动 加载 类，主要为了防止与公共 类中的__autoload()函数冲突
 * 且对目录层级没有限制
 *
 * @author wuqingcheng
 * @create by 2013.04.03
 */

class Dang_Mvc_Autoloader
{
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

        $path = "./libs/";

        preg_match("/^[\\\]?([a-z]+)([_\\\])/si", $className, $m);
        if(!$m){
            throw new Exception($className." no namespace!");
        }
        $namespace = $m[1];
        $separator = $m[2];

        //捕获zend
        if(strtolower($namespace) == "zend"){
            if($separator == "_"){
                $path = realpath($path)."/Zend/zend1/";
            }else{
                $path = realpath($path)."/Zend/zend2/";
            }
        }

        $filename = realpath($path)."/". preg_replace('/[_\\\]/', DIRECTORY_SEPARATOR, $className) . '.php';
        if(!file_exists($filename)){
            throw new Exception("File: ".$filename." not found!");
        }

        return include $filename;
    }
}

?>
