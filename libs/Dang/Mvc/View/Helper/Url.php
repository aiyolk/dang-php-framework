<?php

/*
 * 视图助手 url助手
 * 使用方法: echo $this->url($params);
 */

class Dang_Mvc_View_Helper_Url
{
    public function _invoke($params, $route = '')
    {
        if(!$route){
            $route = Dang_Mvc_Param::instance()->getRoute();
        }
        if(!$route){
            $route = "default";
        }
        $router = new \Dang\Mvc\Router();
        $str = $router->toUrl($params, $route);
        return $str;
    }

}
