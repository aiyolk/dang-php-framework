<?php

/**
 * 视图模型，用于输出xml
 *
 * @author wuqingcheng
 * @date 2013.04.09
 */

class Dang_Mvc_View_Model_XmlModel extends Dang_Mvc_View_Model_HtmlModel
{
    public function getTemplateExtension()
    {
        return "pxml";
    }
}

?>
