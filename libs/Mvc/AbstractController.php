<?php

/**
 * 前端控制器抽象类, 基础类
 *
 * @author: wuqingcheng 
 * @date: 2013.04.09
 */

abstract class Mvc_AbstractController 
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
        $action = Mvc_Param::instance()->getAction();
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
        $action = Mvc_Param::instance()->getAction();
        $method = $action."Action";
        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        return $this->$method();
    }
    
    /*
     * 请求分发之后需要执行的代码
     * 可以用于关闭数据库等类似场景
     */
    public function postDispatch() 
    {
        
    }

}

?>
