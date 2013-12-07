<?php

class Dang_Mvc_View_Helper_Standalone_HeadTitle
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

    public function append($title)
    {
        $this->_items[] = $title;
    }

    public function prepend($title)
    {
        array_unshift($this->_items, $title);
    }

    public function toString()
    {
        return "<title>".join(" - ", $this->_items)."</title>";
    }
}
