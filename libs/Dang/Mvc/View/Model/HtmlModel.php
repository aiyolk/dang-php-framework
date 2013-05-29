<?php

/**
 * 视图模型，用于输出Html
 *
 * @author wuqingcheng
 * @date 2013.04.09
 */

class Dang_Mvc_View_Model_HtmlModel extends Dang_Mvc_View_Model_AbstractModel
{
    protected  $template;

    public function setTemplate($filname)
    {
        $this->template = (string)$filname;
        return $this;
    }

    public function getTemplate()
    {
        if(!isset($this->template)){
            throw new Exception("Please set the template!");
        }
        
        return $this->template;
    }
}


?>
