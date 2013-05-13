<?php

/*
 * 视图助手 占位助手
 * 使用方法: echo $this->Partial("Www_Test_Product", array('productId'=>1111));
 */

class Dang_Mvc_View_Helper_Partial
{
    public function _invoke($filename = null, $values = null)
    {
        if (0 == func_num_args()) {
            return $this;
        }

        $phpRenderer = new Dang_Mvc_PhpRenderer();
        
        return $phpRenderer->renderPhtml($filename, $values);
    }
    
}
