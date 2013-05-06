<?php

/**
 * 视图渲染器
 * 用于加载模板，输出html
 *
 * @author wuqingcheng
 * @date 2013.04.09
 * @email wqc200@gmail.com
 */

class Mvc_PhpRenderer
{
    private $__vars;
    private $__viewHelpers = array();
    
    /*
     * 重载__get()
     */
    public function __get($name)
    {
        $vars = $this->getVars();
        return $vars[$name];
    }

    /**
     * 重载__set()
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $vars = $this->getVars();
        $vars[$name] = $value;
    }
    
    public function renderModel(Mvc_Model_ModelInterface $model)
    {
        $variables = $model->getVariables();
        $this->setVars($variables);
        
        $template = $model->getTemplate();
        
        ob_start();
        include $template;
        $content = ob_get_clean();
        
        return $content;
    }
    
    public function renderPhtml($filename, $values)
    {
        $this->setVars($values);
        
        //获取扩展名
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if($extension == ""){
            $extension = "phtml";
        }
        
        //组织模板
        $template = realpath("./tpl")."/".$filename. '.'.$extension;
        
        ob_start();
        include $template;
        $content = ob_get_clean();

        return $content;
    }
    
    public function setVars($variables)
    {
        $this->__vars = $variables;
        return $this;
    }
    
    public function getVars()
    {
        return $this->__vars;
    }
    
    public function __call($method, $argv)
    {
        $layout = new Mvc_View_Helper_Layout();
        $this->__viewHelpers['layout'] = array($layout, "_invoke");
        
        $partial = new Mvc_View_Helper_Partial();
        $this->__viewHelpers['partial'] = array($partial, "_invoke");
        
        $pagination = new Mvc_View_Helper_PaginationControl();
        $this->__viewHelpers['paginationControl'] = array($pagination, "_invoke");
        
        $pagination = new Mvc_View_Helper_Url();
        $this->__viewHelpers['url'] = array($pagination, "_invoke");
        
        $headMeta = new Mvc_View_Helper_HeadMeta();
        $this->__viewHelpers['headMeta'] = array($headMeta, "_invoke");

        $headTitle = new Mvc_View_Helper_HeadTitle();
        $this->__viewHelpers['headTitle'] = array($headTitle, "_invoke");

        if (!isset($this->__viewHelpers[$method])) {
            return("Error: View helper '$method' not found!");
        }
        if (is_callable($this->__viewHelpers[$method])) {
            return call_user_func_array($this->__viewHelpers[$method], $argv);
        }
        return $this->__viewHelpers[$method];
    }
}

?>
