<?php

interface Dang_Mvc_Data_OpenApi_OpenApiInterface
{
    public function getVariable($name, $default = null);

    public function setVariable($name, $value);

    public function setVariables($variables);

    public function getVariables();
}
