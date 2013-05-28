<?php

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
