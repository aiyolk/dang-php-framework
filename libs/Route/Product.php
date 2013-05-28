<?php

/*
 * 基本路由
 * 提供对形如 http://www.site.com/www/test/test/?param=value 的url的创建和解析
 * @author wuqingcheng
 * @date 2013.05.28
 * @email wqc200@gmail.com
 */

namespace Route;

class Product
{
    public function __construct()
    {

    }

    public function toUrl($param)
    {
        if(!is_array($param)){
            $param = (array) $param;
        }

        $productId = "";
        if(isset($param['productId'])){
            $productId = $param['productId'];
        }

        $serverUrl = \Dang_Mvc_View_HelperManager::instance()->getHelper("serverUrl");
        $url = $serverUrl."/product/".$productId.".html";

        return $url;
    }

    public function fromUrl($url)
    {
        $request_url = $url;
        if(preg_match("/\/product\/([0-9]+).html/si", $request_url, $match)){
            $productId = $match['1'];
            \Dang_Mvc_Request::instance()->setParamGet("module", "www");
            \Dang_Mvc_Request::instance()->setParamGet("controller", "test");
            \Dang_Mvc_Request::instance()->setParamGet("action", "product");
            \Dang_Mvc_Request::instance()->setParamGet("productId", $productId);

            return true;
        }
    }

}

?>