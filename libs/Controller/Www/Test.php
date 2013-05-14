<?php

/**
 * Test 控制器
 *
 * @author wuqingcheng
 * @create by 2013.04.03
 * @modify by 2013.05.14
 */

class Controller_Www_Test  extends Controller_Www_Abstract
{
    /*
     * 测试：获取url参数，分页，视图助手partial
     */
    public function TestAction()
    {
        $htmlModel = new Dang_Mvc_View_Model_HtmlModel();

        $htmlModel->message = "这里调用的模板是：./tpl/Www/Test/Test.html";
        
        $testId = Dang_Mvc_Request::instance()->getParamGet("testId", "222");
        $htmlModel->testId = $testId;

        $paginator = new Dang_Paginator_Paginator();
        $paginator->setTotalItemCount(60);
        $paginator->setItemCountPerPage(6);
        $paginator->setCurrentPageNumber(Dang_Mvc_Request::instance()->getParamGet("page", 1));
        $htmlModel->paginator = $paginator;
        $htmlModel->requestParams = Dang_Mvc_Request::instance()->getParamsGet();
        
        return $htmlModel;
    }
    
    /*
     * 测试：切换模板
     */
    public function TestTplAction()
    {
        $htmlModel = new Dang_Mvc_View_Model_HtmlModel();
        
        Dang_Mvc_Template::instance()->setAction("Product");
        
        $htmlModel->message = "这里调用的模板是：./tpl/Www/Test/Product.html";
        
        return $htmlModel;
    }

    /*
     * 测试：pdo连接出错提示
     */
    public function TestPdoAction()
    {
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
        
        return "";
    }
    
    /*
     * 测试：Zend加载是否正确
     */
    public function TestZendAction()
    {
        $config = \Zend\Config\Factory::fromFile('./config/mysql.php', true);
        print_r($config);
        
        return "";
    }

     /*
     * 测试：测试mysql连接是否成功
     */
    public function TestMysqlAction()
    {
        $name = "www";
        $db = \Dang\Quick::mysql($name);
        print_r($db);
        
        return "";
    }
    
}

?>
