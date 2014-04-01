<?php

class Dang_Mvc_View_Helper_Standalone_InlineScript
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

    public function append($content)
    {
        $this->_items[] = $content;
    }

    public function prepend($content)
    {
        array_unshift($this->_items, $content);
    }
    
    public function toString()
    {
        $string = "";
        for($i=0;$i<count($this->_items);$i++)
        {
            $content = $this->_items[$i];
            $string .= '<script type="text/javascript">';
            $string .= "\n";
            $string .= $content;
            $string .= '</script>';
        }
        
        return $string;
    }
}
