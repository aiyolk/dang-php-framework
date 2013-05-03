<?php

/**
 * Www 是命名空间，autoload将根据此来include类文件
 *
 * @author wuqingcheng
 */

class Www_Test  extends Www_AbstractController
{
    public function TestAction()
    {
        $testId = Mvc_Request::instance()->getParamGet("testId", "222");
        $this->actionHtmlModel->testId = $testId;
        $this->actionHtmlModel->message = "This is a test page!\n";
        
        $this->actionHtmlModel->productId = 2222;
        #Mvc_Template::instance()->setAction("product");
        
        $paginator = new Dang_Paginator_Paginator();
        $paginator->setTotalItemCount(200);
        $paginator->setCurrentPageNumber(Mvc_Request::instance()->getParamGet("page", 1));
        $this->actionHtmlModel->paginator = $paginator;
        $this->actionHtmlModel->requestParams = Mvc_Request::instance()->getParamsGet();
        
        return $this->actionHtmlModel;
    }

}

?>
