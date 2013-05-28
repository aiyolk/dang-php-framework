<?php

/*
 * mvc 参数保存器
 * @author wuqingcheng
 * @date 2013.04.22
 * @email wqc200@gmail.com
 */

namespace Dang\Mvc\Route;

class Base
{
    public function __construct()
    {

    }

    public function toUrl($param)
    {
        if(!is_array($param)){
            $param = (array) $param;
        }

        $module = \Dang_Mvc_Param::instance()->getModule();
        $controller = \Dang_Mvc_Param::instance()->getController();
        $action = \Dang_Mvc_Param::instance()->getAction();

        $query = $param;
        if(isset($param['module'])){
            $module = $param['module'];
            unset($query['module']);
        }
        if(isset($param['controller'])){
            $controller = $param['controller'];
            unset($query['controller']);
        }
        if(isset($param['action'])){
            $action = $param['action'];
            unset($query['action']);
        }

        $module = \Dang_Mvc_Utility::paramMvcToUrl($module);
        $controller = \Dang_Mvc_Utility::paramMvcToUrl($controller);
        $action = \Dang_Mvc_Utility::paramMvcToUrl($action);

        $serverUrl = \Dang_Mvc_View_HelperManager::instance()->getHelper("serverUrl");

        $url = $serverUrl."/".$module."/".$controller."/".$action;
        $str = \Dang_Mvc_Utility::appendParams($query);
        if($str){
            $url .= "/?".$str;
        }
        return $url;
    }

    public function fromUrl($url)
    {
        $request_url = $url;
        if(preg_match("/\/([a-z0-9-_]+)\/([a-z0-9-_]+)\/([a-z0-9-_]+)\/(\?.*?)?$/si", $request_url, $match)){
            $module = $match['1'];
            $controller = $match['2'];
            $action = $match['3'];
            \Dang_Mvc_Request::instance()->setParamGet("module", $module);
            \Dang_Mvc_Request::instance()->setParamGet("controller", $controller);
            \Dang_Mvc_Request::instance()->setParamGet("action", $action);

            return true;
        }
    }

}
