<?php

namespace Dang\Model;

abstract class Ssdb
{
	protected static $_instance = array();
	
    function __construct(){
    	
    }

    public static function instance(){
        $_className = get_called_class();
        if (!isset(self::$_instance[$_className])) {
            self::$_instance[$_className] = new $_className();
        }

        return self::$_instance[$_className];
    }
    
    /**
     * 批量操作开始
     */
    public function batchStart(){
        $this->_ssdb->batch();
    }
    
    /**
     * 批量操作结束
     */
    public function batchEnd(){
        $this->_ssdb->exec();
    }
}
