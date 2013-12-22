<?php

/*
 * 路由示例
 *
 * 提供对形如 http://www.site.com/product/88888.html 的url的创建和解析
 * @author wuqingcheng
 * @add 2013.05.28
 * @email wqc200@gmail.com
 */

namespace Route;

class Product implements \Dang_Mvc_Route_Interface
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

        $serverUrl = \Dang_Mvc_View_Helper::instance()->serverUrl();
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