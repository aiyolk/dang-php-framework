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
    private static $_mobileDetect;
    private static $_ssdb;

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

    /*
     * mobile detect调用器
     * 使用方法参见 ./Apps/MobileDetect/readme.txt
     *
     * @return object self::$_mobileDetect MobileDetect对象
     */
    public static function mobileDetect()
    {
        if (isset(self::$_mobileDetect)) {
            return self::$_mobileDetect;
        }

        require 'MobileDetect/Mobile_Detect.php';
        self::$_mobileDetect = new \Mobile_Detect();

        return self::$_mobileDetect;
    }

    public static function ssdb()
    {
        if (isset(self::$_ssdb)) {
            return self::$_ssdb;
        }
        
        self::$_ssdb = new \Apps\Ssdb\Client();

        return self::$_ssdb;
    }

}

?>
