<?php

/**
 * 视图模型抽象类，用于提供基础方法
 *
 * @author wuqingcheng
 * @date 2013.04.09
 */

abstract class Mvc_Model_AbstractModel implements Mvc_Model_ModelInterface
{
    private $variables;
    
    public function __get($name)
    {
        if (!$this->__isset($name)) {
            return null;
        }

        $variables = $this->getVariables();
        return $variables[$name];
    }

    /**
     * 重写__set()方法
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setVariable($name, $value);
    }
    
    /**
     * 重写 __isset()方法
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        $variables = $this->getVariables();
        return isset($variables[$name]);
    }
    
    /**
     * 获取一个视图变量
     *
     * @param  string       $name
     * @param  mixed|null   $default (optional) default value if the variable is not present.
     * @return mixed
     */
    public function getVariable($name, $default = null)
    {
        $name = (string) $name;
        if (array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        return $default;
    }
    
    /**
     * 获取所有的视图变量
     *
     * @return array|ArrayAccess|Traversable
     */
    public function getVariables()
    {
        return $this->variables;
    }
    
    /**
     * 设置一个视图变量
     *
     * @param  string $name
     * @param  mixed $value
     * @return ViewModel
     */
    public function setVariable($name, $value)
    {
        $this->variables[(string) $name] = $value;
        return $this;
    }
    
    public function setVariables($variables, $overwrite = false)
    {
        if ($overwrite) {
            $this->variables = $variables;
            return $this;
        }

        foreach ($variables as $key => $value) {
            $this->setVariable($key, $value);
        }

        return $this;
    }
}
   

?>
