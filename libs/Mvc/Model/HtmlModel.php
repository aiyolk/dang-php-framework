<?php

/**
 * 视图模型，用于输出Html
 * 
 * @author wuqingcheng
 * @date 2013.04.09
 */

class Mvc_Model_HtmlModel extends Mvc_Model_AbstractModel
{
    protected  $template;
    
    public function setTemplatePath($path)
    {
        $this->templatePath = (string)$path;
        return $this;
    }

    public function setTemplateName($name)
    {
        $this->templateName = (string)$name;
        return $this;
    }

    public function setTemplate($path, $name)
    {
        $this->template = (string)$path. "/". (string)$name. ".phtml";
        return $this;
    }

    public function getTemplate()
    {
        if(isset($this->template)){
            return $this->template;
        }
        
        $this->template = (string)$this->templatePath. "/". ucfirst($this->templateName). ".phtml";
        
        return $this->template;
    }
}
   

?>
