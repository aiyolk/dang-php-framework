<?php

/*
 * 应用快捷启动器
 *
 * @author wuqingcheng wqc200@gmail.com
 * @date 2013.05.24
 */

namespace Dang\Quick;

class AppsLoader
{
    private static $_appsDir = "../";
    private static $_appa = array();
   
    public static function setDir($appsDir){
        self::$_appsDir = $appsDir;
    }
    
    public static function loader($appName){
    	if (in_array($appName, self::$_appa)) {
            return true;
        }

        $filename = rtrim(self::$_appsDir, "/") . DIRECTORY_SEPARATOR . $appName .DIRECTORY_SEPARATOR ."autoloader.php";
        if(!file_exists($filename)){
        	throw new \Exception("File: ".$filename." not found!");
        }
        require $filename;
        self::$_appa[] = $appName;
        
        return true;
    }
}

?>
