<?php

/**
 * 所有代码的执行入口，所有的代码最终都是通过此类运行
 *
 * @author wuqingcheng
 * @date: 2013.04.09
 */

class Mvc_Enter
{
    /*
     * 构造入口
     */
    function __construct()
    {
        //Console或浏览器url里的module/action都在get里传递
        $module = Mvc_Request::instance()->getParamGet("module", "www");
        $controller = Mvc_Request::instance()->getParamGet("controller", "test");
        $action = Mvc_Request::instance()->getParamGet("action", "test");
        
        $module = Mvc_Utility::paramUrlToMvc($module);
        $this->moduleName = ucfirst($module);
        //组合控制器名称
        $controller = Mvc_Utility::paramUrlToMvc($controller);
        $this->controllerName = ucfirst($controller);
        //组合控制器方法名
        $action = Mvc_Utility::paramUrlToMvc($action);
        $this->actionName = ucfirst($action);
        
        //将mvc参数传递给Mvc_Param
        Mvc_Param::instance()->setModule($this->moduleName);
        Mvc_Param::instance()->setController($this->controllerName);
        Mvc_Param::instance()->setAction($this->actionName);
    }
    
    //执行器
    public function run()
    {
        $classer = $this->moduleName."_".$this->controllerName;
        
        //实例化控制器
        $controller = new $classer();
        
        //执行 $method 方法之前所需要进行的操作，如“打开数据库连接”
        $controller->preDispatch();
        //执行 $method 方法
        $result = $controller->onDispatch();
        //执行 $method 方法之后所需要进行的操作，如“关闭数据库连接”
        $controller->postDispatch();

        //根据返回的model对象的类型，决定使用什么样的输出方法
        if($result instanceof Mvc_Model_XmlModel){
            
        }elseif($result instanceof Mvc_Model_JsonModel){
            echo json_encode($result->result);
            
        }elseif($result instanceof Mvc_Model_HtmlModel){
            $layout = Mvc_Template::instance()->getLayout();
            $module = Mvc_Template::instance()->getModule();
            $controller = Mvc_Template::instance()->getController();
            $action = Mvc_Template::instance()->getAction();
            
            $actionModel = $result;
            $path = "./tpl/".$module."/".$controller;
            $actionModel->setTemplatePath($path);
            $actionModel->setTemplateName($action);
            $actionModel->setCaptureTo('content');
            
            $layoutModel = Mvc_Service::instance()->get("layoutModel");
            $path = "./tpl/".$module;
            $layoutModel->setTemplatePath($path);
            $layoutModel->setTemplateName($layout);
            $layoutModel->addChild($actionModel, 'content');
            
            $view = new Mvc_View_View();
            $content = $view->render($layoutModel);
            
            /*
            //获取method里的html代码 
            $actionHtmlModel = $result;
            $path = "./tpl/".$module."/".$controller;
            $actionHtmlModel->setTemplatePath($path);
            $actionHtmlModel->setTemplateName($action);
            $phpRenderer = new Mvc_PhpRenderer();
            $actionContent = $phpRenderer->renderModel($actionHtmlModel);
            
            //获取layout里的html代码，并将method里的代码传递到模板内容区
            $layoutHtmlModel = Mvc_Template::instance()->getLayoutModel();
            $path = "./tpl/".$module;
            $layoutHtmlModel->setTemplatePath($path);
            $layoutHtmlModel->setTemplateName($layout);
            $layoutHtmlModel->content = $actionContent;
            $phpRenderer = new Mvc_PhpRenderer();
            $content = $phpRenderer->renderModel($layoutHtmlModel);
            */
            
            echo $content;
            
        }else{
            echo $result;
            
        }
    }
}

?>
