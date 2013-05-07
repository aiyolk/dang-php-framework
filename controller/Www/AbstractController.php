<?php

/**
 * api前端控制器抽象类, 专门用于api的输出
 *
 * @author: wuqingcheng
 * @date: 2013.04.09
 */
abstract class Www_AbstractController extends Mvc_AbstractController
{
    protected $actionHtmlModel;
    protected $layoutHtmlModel;

    //构造函数
    function __construct() 
    {
        parent::__construct();
        
        $this->actionHtmlModel = new Mvc_Model_HtmlModel();
    }

}

?>
