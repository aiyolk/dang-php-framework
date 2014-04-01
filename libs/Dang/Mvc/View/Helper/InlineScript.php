<?php

class Dang_Mvc_View_Helper_InlineScript
{
    private $_standalone;
    private $_placement;

    public function _invoke($placement = "append")
    {
        $this->_standalone = Dang_Mvc_View_Helper_Standalone_InlineScript::instance();
        $this->_placement = $placement;
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
    
    public function captureStart()
    {
        ob_start();
        return $this;
    }
    
    public function captureEnd()
    {
        $content = ob_get_clean();
        if($this->_placement == "append"){
            $this->_standalone->append($content);
        }else{
            $this->_standalone->prepend($content);
        }
        return $this;
    }
}
