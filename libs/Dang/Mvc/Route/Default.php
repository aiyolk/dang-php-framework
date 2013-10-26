<?php

/*
 * 默认路由，不需要配制rewrite
 *
 * 提供对形如 http://www.site.com/?module=www&controller=test&action=test 的url的创建和解析
 * @author wuqingcheng
 * @date 2013.05.28
 * @email wqc200@gmail.com
 */

class Dang_Mvc_Route_Default implements \Dang_Mvc_Route_Interface
{
    public function __construct()
    {

    }

    public function toUrl($param)
    {
        if(!is_array($param)){
            $param = (array) $param;
        }

        if(isset($param['module'])){
            $module = $param['module'];
        }else{
            $module = \Dang_Mvc_Param::instance()->getModule();
        }
        $param['module'] = \Dang_Mvc_Utility::paramMvcToUrl($module);
        if(isset($param['controller'])){
            $controller = $param['controller'];
        }else{
            $controller = \Dang_Mvc_Param::instance()->getController();
        }
        $param['controller'] = \Dang_Mvc_Utility::paramMvcToUrl($controller);
        if(isset($param['action'])){
            $action = $param['action'];
        }else{
            $action = \Dang_Mvc_Param::instance()->getAction();
        }
        $param['action'] = \Dang_Mvc_Utility::paramMvcToUrl($action);

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
