<?php

/*
 * 分页助手
 * @author wuqingcheng
 * @date 2013.04.22
 * @email wqc200@163.com
 */
class Dang_Mvc_View_Helper_PaginationControl
{
    public function _invoke(Dang_Paginator_Paginator $paginator, $filename, $requestParams, $urlRoute = 'default')
    {
        $pages = get_object_vars($paginator->getPages());
        $pages['requestParams'] = $requestParams;
        $pages['urlRoute'] = $urlRoute;
        
        $argv = array($filename, $pages);
        return \Dang_Mvc_View_HelperManager::instance()->getHelper("partial", $argv);
    }
}
