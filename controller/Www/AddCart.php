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
        $testId = Mvc_Request::instance()->getParamGet("testId", "222");
        $this->actionHtmlModel->testId = $testId;
        $this->actionHtmlModel->message = "controller: Add_Cart\n";
        
        return $this->actionHtmlModel;
    }

}

?>
