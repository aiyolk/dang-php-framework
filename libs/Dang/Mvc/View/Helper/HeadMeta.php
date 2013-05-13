<?php

class Dang_Mvc_View_Helper_HeadMeta
{
    private $_standalone;
    
    public function _invoke()
    {
        $this->_standalone = Dang_Mvc_View_Helper_Standalone_HeadMeta::instance();
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
    
    public function append($meta)
    {
        $this->_standalone->append($meta);
        
        return $this;
    }
}
