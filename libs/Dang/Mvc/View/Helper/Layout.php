<?php

/*
 * 视图助手 布局助手
 * 使用方法: $this->layout()->content;
 */

class Dang_Mvc_View_Helper_Layout
{
    /*
     * 视图入口，当调用此助手的时候，将会调用此方法
     * 
     */
    public function _invoke() 
    {
        return Dang_Mvc_ServiceManager::instance()->get("layoutModel");
    }
    
}

?>
