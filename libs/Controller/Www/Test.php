<?php

/**
 * Www 是命名空间，autoload将根据此来include类文件
 *
 * @author wuqingcheng
 */

class Controller_Www_Test  extends Controller_Www_Abstract
{
    public function TestAction()
    {
        $htmlModel = new Dang_Mvc_View_Model_HtmlModel();

        $testId = Dang_Mvc_Request::instance()->getParamGet("testId", "222");
        $htmlModel->testId = $testId;
        $htmlModel->message = "Controller: test\n";

        return $htmlModel;
    }

}

?>
