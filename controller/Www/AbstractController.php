<?php

/**
 *
 * @author: wuqingcheng
 * @date: 2013.04.09
 */
abstract class Www_AbstractController extends Mvc_AbstractController
{
    //构造函数
    function __construct() 
    {
        parent::__construct();
        
        //添加布局视图
        $layoutModel = new Mvc_Model_HtmlModel();
        Mvc_Service::instance()->add("layoutModel", $layoutModel);
    }

}

?>
