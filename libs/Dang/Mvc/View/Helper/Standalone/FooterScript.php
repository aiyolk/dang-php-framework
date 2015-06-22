<?php

class Dang_Mvc_View_Helper_Standalone_FooterScript
{
    private static $instance = null;
    
    private $_items;
            
    function __construct() {
        $this->_items = array();
    }

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function append($url)
    {
        $this->_items[] = $url;
    }
    
    public function toString()
    {
        $items = "";
        for($i=0;$i<count($this->_items);$i++){
            $item = $this->_items[$i];
            $items .= $item;
        }
        
        return $items;
    }
}
