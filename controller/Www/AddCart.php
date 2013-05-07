<?php

/**
 * Www 是命名空间，autoload将根据此来include类文件
 *
 * @author wuqingcheng
 */

class Www_AddCart  extends Www_AbstractController
{
    public function TestAction()
    {
        $actionModel = new Mvc_Model_HtmlModel();
        
        $testId = Mvc_Request::instance()->getParamGet("testId", "222");
        $actionModel->testId = $testId;
        $actionModel->message = "controller: Add_Cart\n";
        
        return $actionModel;
    }

}

?>
