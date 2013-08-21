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
    private static $_hbase;
    private static $_hbaseRest;
    private static $_riak;
    private static $_couchbase;
    private static $_cassandra;
    private static $_phpcassa;

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
