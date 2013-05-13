<?php

/*
 * 分页助手
 * @author wuqingcheng
 * @date 2013.04.22
 * @email wqc200@163.com
 */
class Mvc_View_Helper_PaginationControl
{
    public function _invoke(Dang_Paginator_Paginator $paginator, $filename, $requestParams)
    {
        $pages = get_object_vars($paginator->getPages());
        $pages['requestParams'] = $requestParams;
        $phpRenderer = new Mvc_PhpRenderer();
        return $phpRenderer->renderPhtml($filename, $pages);
    }
}
