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
        if(isset($_SERVER['REQUEST_URI'])){
            $request_url = $_SERVER['REQUEST_URI'];
            $router = new \Dang\Mvc\Router();
            $route = $router->fromUrl($request_url);
            Dang_Mvc_Param::instance()->setRoute($route);
        }

        $module = Dang_Mvc_Request::instance()->getParamGet("module", "www");
        $module = Dang_Mvc_Utility::paramUrlToMvc($module);
        $this->moduleName = ucfirst($module);
        Dang_Mvc_Param::instance()->setModule($this->moduleName);

        //设备类型
        $device = Dang_Mvc_Request::instance()->getParamGet("device");
        if(!$device){
            $mobileDetect = \Apps\Quick::mobileDetect();
            if($mobileDetect->isTablet()){
                $device = "tablet";
            }elseif($mobileDetect->isMobile()){
                $device = "mobile";
            }else{
                $device = "pc";
            }
        }
      
        $device = Dang_Mvc_Utility::paramUrlToMvc($device);
        Dang_Mvc_Param::instance()->setDevice($device);

        $controller = Dang_Mvc_Request::instance()->getParamGet("controller", "index");
        $controller = Dang_Mvc_Utility::paramUrlToMvc($controller);
        $this->controllerName = ucfirst($controller);
        Dang_Mvc_Param::instance()->setController($this->controllerName);

        $action = Dang_Mvc_Request::instance()->getParamGet("action", "index");
        $action = Dang_Mvc_Utility::paramUrlToMvc($action);
        $this->actionName = ucfirst($action);
        Dang_Mvc_Param::instance()->setAction($this->actionName);
    }

    //执行器
    public function run()
    {
        $break = true;
        //用于forward，最多forward2次，避免进入死循环
        for($i=0;$i<2;$i++)
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

            //检查mvc参数是否被重写
            if($this->moduleName != Dang_Mvc_Param::instance()->getModule()){
                $break = false;
                $this->moduleName = Dang_Mvc_Param::instance()->getModule();
            }
            if($this->controllerName != Dang_Mvc_Param::instance()->getController()){
                $break = false;
                $this->controllerName = Dang_Mvc_Param::instance()->getController();
            }
            if($this->actionName != Dang_Mvc_Param::instance()->getAction()){
                $break = false;
                $this->actionName = Dang_Mvc_Param::instance()->getAction();
            }

            if($break == true){
                break;
            }
        }

        //根据返回的model对象的类型，决定使用什么样的输出方法
        if($result instanceof Dang_Mvc_View_Model_XmlModel){
        	$actionModel = $result;
            
        	$filename = Dang_Mvc_Template::instance()->setExtension("pxml")->getActionFilename();
        	$actionModel->setTemplate($filename);
        	
        	/*
            $module = Dang_Mvc_Template::instance()->getModule();
            $controller = Dang_Mvc_Template::instance()->getController();
            $action = Dang_Mvc_Template::instance()->getAction();
            Dang_Mvc_Template::instance()->setExtension("pxml");
            
            //获取method里的html代码
            $viewXmlModel = $result;
            $path = "./tpl/".$module."/".$controller;
            $viewXmlModel->setTemplatePath($path);
            $viewXmlModel->setTemplateName($action);
            */
        	
            $phpRenderer = new Dang_Mvc_PhpRenderer();
            $content = $phpRenderer->renderModel($actionModel);
            
            header( "content-type: application/xml; charset=UTF-8" );
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo $content;

        }elseif($result instanceof Dang_Mvc_View_Model_TxtModel){
            $txtModel = $result;
            $filename = Dang_Mvc_Template::instance()->setExtension("ptxt")->getActionFilename();
            $txtModel->setTemplate($filename);
            $phpRenderer = new Dang_Mvc_PhpRenderer();
            $content = $phpRenderer->renderModel($txtModel);
            echo $content;

        }elseif($result instanceof Dang_Mvc_View_Model_JsonModel){
            echo json_encode($result->getVariables());

        }elseif($result instanceof Dang_Mvc_View_Model_HtmlModel){
            $actionModel = $result;
            $filename = Dang_Mvc_Template::instance()->getActionFilename();
            $actionModel->setTemplate($filename);
            $actionModel->setCaptureTo('content');

            $layoutModel = Dang_Mvc_ServiceManager::instance()->get("layoutModel");
            $filename = Dang_Mvc_Template::instance()->getLayoutFilename();
            $layoutModel->setTemplate($filename);
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
