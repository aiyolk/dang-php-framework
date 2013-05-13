<?php

/**
 * 所有代码的执行入口，所有的代码最终都是通过此类运行
 *
 * @author wuqingcheng
 * @date: 2013.04.09
 */

class Dang_Mvc_Enter
{
    /*
     * 构造入口
     */
    function __construct()
    {
        //Console或浏览器url里的module/action都在get里传递
        $module = Dang_Mvc_Request::instance()->getParamGet("module", "www");
        $controller = Dang_Mvc_Request::instance()->getParamGet("controller", "test");
        $action = Dang_Mvc_Request::instance()->getParamGet("action", "test");

        $module = Dang_Mvc_Utility::paramUrlToMvc($module);
        $this->moduleName = ucfirst($module);
        //组合控制器名称
        $controller = Dang_Mvc_Utility::paramUrlToMvc($controller);
        $this->controllerName = ucfirst($controller);
        //组合控制器方法名
        $action = Dang_Mvc_Utility::paramUrlToMvc($action);
        $this->actionName = ucfirst($action);

        //将mvc参数传递给Mvc_Param
        Dang_Mvc_Param::instance()->setModule($this->moduleName);
        Dang_Mvc_Param::instance()->setController($this->controllerName);
        Dang_Mvc_Param::instance()->setAction($this->actionName);
    }

    //执行器
    public function run()
    {
        $classer = "Controller_".$this->moduleName."_".$this->controllerName;

        //实例化控制器
        $controller = new $classer();
        //执行 $method 方法之前所需要进行的操作，如“打开数据库连接”
        $controller->preDispatch();
        //执行 $method 方法
        $result = $controller->onDispatch();
        //执行 $method 方法之后所需要进行的操作，如“关闭数据库连接”
        $controller->postDispatch();

        //根据返回的model对象的类型，决定使用什么样的输出方法
        if($result instanceof Dang_Mvc_View_Model_XmlModel){
            header( "content-type: application/xml; charset=UTF-8" );
            echo '<?xml version="1.0" encoding="UTF-8"?>';

            $module = Dang_Mvc_Template::instance()->getModule();
            $controller = Dang_Mvc_Template::instance()->getController();
            $action = Dang_Mvc_Template::instance()->getAction();

            //获取method里的html代码
            $viewXmlModel = $result;
            $path = "./tpl/".$module."/".$controller;
            $viewXmlModel->setTemplatePath($path);
            $viewXmlModel->setTemplateName($action);
            $phpRenderer = new Dang_Mvc_PhpRenderer();
            $content = $phpRenderer->renderModel($viewXmlModel);
            echo $content;

        }elseif($result instanceof Dang_Mvc_View_Model_TxtModel){
            $module = Dang_Mvc_Template::instance()->getModule();
            $controller = Dang_Mvc_Template::instance()->getController();
            $action = Dang_Mvc_Template::instance()->getAction();

            //获取method里的html代码
            $txtModel = $result;
            $path = "./tpl/".$module."/".$controller;
            $txtModel->setTemplatePath($path);
            $txtModel->setTemplateName($action);
            $phpRenderer = new Dang_Mvc_PhpRenderer();
            $content = $phpRenderer->renderModel($txtModel);
            echo $content;

        }elseif($result instanceof Dang_Mvc_View_Model_JsonModel){
            echo json_encode($result->getVariables());

        }elseif($result instanceof Dang_Mvc_View_Model_HtmlModel){
            $layout = Dang_Mvc_Template::instance()->getLayout();
            $module = Dang_Mvc_Template::instance()->getModule();
            $controller = Dang_Mvc_Template::instance()->getController();
            $action = Dang_Mvc_Template::instance()->getAction();

            $actionModel = $result;
            $path = "./tpl/".$module."/".$controller;
            $actionModel->setTemplatePath($path);
            $actionModel->setTemplateName($action);
            $actionModel->setCaptureTo('content');

            $layoutModel = Dang_Mvc_ServiceManager::instance()->get("layoutModel");
            $path = "./tpl/".$module;
            $layoutModel->setTemplatePath($path);
            $layoutModel->setTemplateName($layout);
            $layoutModel->addChild($actionModel, 'content');
            $view = new Dang_Mvc_View_View();
            $content = $view->render($layoutModel);

            echo $content;

        }else{
            echo $result;

        }
    }
}

?>
