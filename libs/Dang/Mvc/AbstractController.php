<?php

/**
 * 前端控制器抽象类, 基础类
 *
 * @author: wuqingcheng
 * @date: 2013.04.09
 */

abstract class Dang_Mvc_AbstractController
{

    //构造函数
    function __construct()
    {

    }

    /*
     * action不存在的时候执行的方法
     */
    public function notFoundAction()
    {
        $action = Dang_Mvc_Param::instance()->getAction();
        header("HTTP/1.0 404 Not Found");
        exit("Action: \"{$action}\" not found!\n");
    }

    /*
     * 请求分发之后需要执行的代码
     * 可以用于打开数据库等类似场景
     */
    public function preDispatch()
    {

    }

    /*
     * 执行请求
     */
    public function onDispatch()
    {
        $action = Dang_Mvc_Param::instance()->getAction();
        $method = $action."Action";
        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        \Dang\Clock::getOne('Total')->start();
        $result = $this->$method();
        \Dang\Clock::getOne('Total')->end();

        return $result;
    }

    /*
     * 请求分发之后需要执行的代码
     * 可以用于关闭数据库等类似场景
     */
    public function postDispatch()
    {

    }

    /*
     * 视图助手
     */
    public function getHelper()
    {
        $helper = new Dang_Mvc_View_Helper();
        
        return $helper;
    }
}

?>
