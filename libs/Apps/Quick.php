<?php

/*
 * 应用快捷启动器
 * 
 * @author wuqingcheng wqc200@gmail.com
 * @date 2013.05.24
 */

namespace Apps;

class Quick
{
    private static $_phpmailer;
    
    /*
     * phpmailer调用器
     * 使用方法参见 ./Apps/Phpmailer/readme.txt
     * 
     * @return object self::$_phpmailer phpmailer对象
     */
    public static function phpmailer()
    {
        if (isset(self::$_phpmailer)) {
            return self::$_phpmailer;
        }
        
        require 'Phpmailer/class.phpmailer.php';
        self::$_phpmailer = new \PHPMailer();
        
        return self::$_phpmailer;
    }
}

?>
