<?php

/*
 * 视图助手 布局助手
 * 使用方法: $this->layout()->content;
 */

class Mvc_View_Helper_Layout
{
    /*
     * 视图入口，当调用此助手的时候，将会调用此方法
     * 
     */
    public function _invoke() 
    {
        $layoutHtmlModel = Mvc_Template::instance()->getLayoutModel();
        return $layoutHtmlModel;
    }
    
}

?>
