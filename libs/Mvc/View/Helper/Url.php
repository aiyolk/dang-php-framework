<?php

/*
 * 视图助手 url助手
 * 使用方法: echo $this->url($params);
 */

class Mvc_View_Helper_Url
{
    public function _invoke($params)
    {
        $str = Mvc_Utility::appendParams($params);
        return "/?".$str;
    }
    
}
