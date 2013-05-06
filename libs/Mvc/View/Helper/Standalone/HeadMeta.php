<?php

class Mvc_View_Helper_Standalone_HeadMeta
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
    
    public function append($meta)
    {
        $this->_items[] = $meta;
    }
    
    public function toString()
    {
        return join("", $this->_items);
    }
}
