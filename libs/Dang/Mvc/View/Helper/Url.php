<?php

/*
 * 视图助手 url助手
 * 使用方法: echo $this->url($params);
 */

class Dang_Mvc_View_Helper_Url
{
    public function _invoke($params)
    {
        $str = Dang_Mvc_Utility::appendParams($params);
        return "/?".$str;
    }
    
}
