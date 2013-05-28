<?php

/*
 * 路由的接口
 * 
 * 所有的路由配制都使用此接口
 * 
 * @author wuqingcheng
 * @add 2013.05.28
 * @email wqc200@gmail.com
 */

interface Dang_Mvc_Route_Interface
{
    public function toUrl($param);

    public function fromUrl($url);
}
