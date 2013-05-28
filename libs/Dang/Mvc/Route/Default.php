<?php

/*
 * 默认路由，不需要配制rewrite
 * 
 * 提供对形如 http://www.site.com/?module=www&controller=test&action=test 的url的创建和解析
 * @author wuqingcheng
 * @date 2013.05.28
 * @email wqc200@gmail.com
 */

class Dang_Mvc_Route_Default
{
    public function __construct()
    {

    }

    public function toUrl($param)
    {
        if(!is_array($param)){
            $param = (array) $param;
        }

        if(!isset($param['module'])){
            $param['module'] = \Dang_Mvc_Param::instance()->getModule();
        }
        if(!isset($param['controller'])){
            $param['controller'] = \Dang_Mvc_Param::instance()->getController();
        }
        if(!isset($param['action'])){
            $param['action'] = \Dang_Mvc_Param::instance()->getAction();
        }

        $serverUrl = \Dang_Mvc_View_HelperManager::instance()->getHelper("serverUrl");

        $url = $serverUrl;
        $str = \Dang_Mvc_Utility::appendParams($param);
        if($str){
            $url .= "/?".$str;
        }
        return $url;
    }

    public function fromUrl($url)
    {
        return true;
    }

}
