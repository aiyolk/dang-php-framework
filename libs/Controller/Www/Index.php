<?php

/**
 * Index Controller
 *
 * @author wqc200@gmail.com
 * @create by 2013.10.28
 * @modify by 2013.10.28
 */

class Controller_Www_Index  extends Controller_Www_Abstract
{
    /*
     * 测试：获取url参数，分页，视图助手partial
     */
    public function IndexAction()
    {
        $htmlModel = new Dang_Mvc_View_Model_HtmlModel();

        $htmlModel->message = "这里调用的模板是：./tpl/Www/Test/Test.html";
        
        $testId = Dang_Mvc_Request::instance()->getParamGet("testId", "222");
        $htmlModel->testId = $testId;

        //分页方法
        $paginator = new Dang_Paginator_Paginator();
        $paginator->setTotalItemCount(60);
        $paginator->setItemCountPerPage(6);
        $paginator->setCurrentPageNumber(Dang_Mvc_Request::instance()->getParamGet("page", 1));
        $htmlModel->paginator = $paginator;
        $htmlModel->requestParams = Dang_Mvc_Request::instance()->getParamsGet();
        
        return $htmlModel;
    }
    
    public function ViewFormAction()
    {
        $htmlModel = new Dang_Mvc_View_Model_HtmlModel();
        
        return $htmlModel;
    }
    
    public function GetJsonAction()
    {
        $jsonModel = new Dang_Mvc_View_Model_JsonModel();
        
        $username = Dang_Mvc_Request::instance()->getParamPost("username");
        if(!$username){
            $jsonModel->errorCode = 100;
            $jsonModel->message = "请输入用户名";
            return $jsonModel;
        }
        
        $jsonModel->errorCode = 0;
        $jsonModel->message = "success";
        
        return $jsonModel;
    }
        
}

?>
