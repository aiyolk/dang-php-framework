<?php

/**
 * 视图渲染器
 * 用于加载模板，输出html
 *
 * @author wuqingcheng
 * @date 2013.04.09
 * @email wqc200@gmail.com
 */

class Dang_Mvc_PhpRenderer
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

    public function renderModel(Dang_Mvc_View_Model_ModelInterface $model)
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

        ob_start();
        include $filename;
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
        if (!isset($this->__viewHelpers[$method])) {
            $this->__viewHelpers[$method] = Dang_Mvc_View_HelperManager::instance()->getInvoke($method);
        }
        if (is_callable($this->__viewHelpers[$method])) {
            return call_user_func_array($this->__viewHelpers[$method], $argv);
        }
        return $this->__viewHelpers[$method];
    }
}

?>
