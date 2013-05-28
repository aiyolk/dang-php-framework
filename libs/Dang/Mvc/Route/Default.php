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

        if(!isset($query['module'])){
            $query['module'] = \Dang_Mvc_Param::instance()->getModule();
        }
        if(!isset($query['controller'])){
            $query['controller'] = \Dang_Mvc_Param::instance()->getController();
        }
        if(!isset($query['action'])){
            $query['action'] = \Dang_Mvc_Param::instance()->getAction();
        }

        $serverUrl = \Dang_Mvc_View_HelperManager::instance()->getHelper("serverUrl");

        $url = $serverUrl;
        $str = \Dang_Mvc_Utility::appendParams($query);
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
