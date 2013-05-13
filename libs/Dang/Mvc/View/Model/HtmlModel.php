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
    protected  $templatePath;
    protected  $templateName;
    protected  $templateExtention;

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

    public function setTemplateExtension($ext)
    {
        $this->templateExtension = (string)$ext;
        return $this;
    }

    public function getTemplateExtension()
    {
        if($this->templateExtension){
            return $this->templateExtension;
        }

        return "phtml";
    }

    public function setTemplate($path, $name)
    {
        $this->template = (string)$path. "/". (string)$name. ".".$this->getTemplateExtension();
        return $this;
    }

    public function getTemplate()
    {
        if(isset($this->template)){
            return $this->template;
        }

        $this->template = (string)$this->templatePath. "/". ucfirst($this->templateName). ".".$this->getTemplateExtension();

        return $this->template;
    }
}


?>
