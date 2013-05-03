<?php

class Mvc_View_Helper_HeadMeta
{
    public function _invoke()
    {
        return $this;
    }
    
    public function __toString()
    {
        return "aaaaaaaaaaa";
    }
    
    public function __call($method, $argv)
    {
        $layout = new Mvc_View_Helper_Layout();
        $this->__viewHelpers['layout'] = array($layout, "_invoke");
        
        $partial = new Mvc_View_Helper_Partial();
        $this->__viewHelpers['partial'] = array($partial, "_invoke");
        
        $pagination = new Mvc_View_Helper_PaginationControl();
        $this->__viewHelpers['paginationControl'] = array($pagination, "_invoke");
        
        $pagination = new Mvc_View_Helper_Url();
        $this->__viewHelpers['url'] = array($pagination, "_invoke");
        
        $headMeta = new Mvc_View_Helper_HeadMeta();
        $this->__viewHelpers['headMeta'] = array($headMeta, "_invoke");

        if (!isset($this->__viewHelpers[$method])) {
            return("Error: View helper '$method' not found!");
        }
        if (is_callable($this->__viewHelpers[$method])) {
            return call_user_func_array($this->__viewHelpers[$method], $argv);
        }
        return $this->__viewHelpers[$method];
    }
}
