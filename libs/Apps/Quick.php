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
    private static $_b8;
    private static $_phpExcel;
    private static $_upyun;
    private static $_simpleHtmlDom;
    private static $_phpmailer;
    private static $_mobileDetect;
    private static $_ssdb;
    private static $_hbase;
    private static $_hbaseRest;
    private static $_riak;
    private static $_couchbase;
    private static $_cassandra;
    private static $_phpcassa;

    public static function b8()
    {
        if(isset(self::$_b8)) {
            return self::$_b8;
        }

        self::$_b8 = new \Apps\B8\Client();

        return self::$_b8;
    }

    public static function phpExcel()
    {
        if(isset(self::$_phpExcel)) {
            return self::$_phpExcel;
        }

        require 'PhpExcel/PHPExcel.php';
        self::$_phpExcel = new \PHPExcel();

        return self::$_phpExcel;
    }

    public static function upyun()
    {
        if(isset(self::$_upyun)) {
            return self::$_upyun;
        }

        self::$_upyun = new \Apps\Upyun\Client();

        return self::$_upyun;
    }

    public static function simpleHtmlDom()
    {
        if(isset(self::$_simpleHtmlDom)) {
            return self::$_simpleHtmlDom;
        }

        require 'SimpleHtmlDom/simple_html_dom.php';
        self::$_simpleHtmlDom = new \simple_html_dom();

        return self::$_simpleHtmlDom;
    }

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

    public static function hbase()
    {
        if (isset(self::$_hbase)) {
            return self::$_hbase;
        }

        self::$_hbase = new \Apps\Hbase\Client();

        return self::$_hbase;
    }

    public static function hbaseRest()
    {
        if (isset(self::$_hbaseRest)) {
            return self::$_hbaseRest;
        }

        self::$_hbaseRest = new \Apps\HbaseRest\Client();

        return self::$_hbaseRest;
    }

    public static function riak()
    {
        if (isset(self::$_riak)) {
            return self::$_riak;
        }

        self::$_riak = new \Apps\Basho\Client();

        return self::$_riak;
    }

    public static function couchbase()
    {
        if (isset(self::$_couchbase)) {
            return self::$_couchbase;
        }

        self::$_couchbase = new \Apps\Couchbase\Client();

        return self::$_couchbase;
    }

    public static function cassandra()
    {
        if (isset(self::$_cassandra)) {
            return self::$_cassandra;
        }

        self::$_cassandra = new \Apps\Cassandra\Client();

        return self::$_cassandra;
    }

    public static function phpcassa()
    {
        if (isset(self::$_phpcassa)) {
            return self::$_phpcassa;
        }

        self::$_phpcassa = new \Apps\Phpcassa\Client();

        return self::$_phpcassa;
    }
}

?>
