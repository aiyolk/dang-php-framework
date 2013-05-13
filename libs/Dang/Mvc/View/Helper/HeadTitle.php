<?php

class Dang_Mvc_View_Helper_HeadTitle
{
    private $_standalone;
    
    public function _invoke()
    {
        $this->_standalone = Dang_Mvc_View_Helper_Standalone_HeadTitle::instance();
        return $this;
    }
    
    public function __toString()
    {
        return $this->_standalone->toString();
    }
    
    public function __call($method, $args)
    {
        echo $method;
        print_r($args);
    }
    
    public function append($title)
    {
        $this->_standalone->append($title);
        
        return $this;
    }
    
    public function prepend($title)
    {
        $this->_standalone->prepend($title);
        
        return $this;
    }
}
