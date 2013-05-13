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

        $paginator = new Dang_Paginator_Paginator();
        $paginator->setTotalItemCount(200);
        $paginator->setCurrentPageNumber(Dang_Mvc_Request::instance()->getParamGet("page", 1));
        $htmlModel->paginator = $paginator;
        $htmlModel->requestParams = Dang_Mvc_Request::instance()->getParamsGet();
        
        $user = "root";
        $pass = "pass";
        try {
            $db = new PDO('mysql:host=localhost;dbname=test', $user, $pass);
            $db = null;
        } catch (PDOException $e) {
            print_r($e);
            print "Error: " . $e->getMessage() . "<br/>";
            die();
        }
        
        return $htmlModel;
    }

}

?>
