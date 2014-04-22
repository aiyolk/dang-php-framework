<?php

namespace Dang;

use Dang\Exception;

abstract class Build
{
    protected function exception($message)
    {
        $class = get_called_class();
        throw new Exception\BadMethodCallException($class.": ".$message);
    }

    public function __set($key, $value)
    {
        $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        if (!method_exists($this, $setter)) {
            $this->exception(
                'The option "' . $key . '" does not '
                . 'have a matching ' . $setter . ' setter method '
                . 'which must be defined'
            );
        }
        $this->{$setter}($value);
    }

    public function __get($key)
    {
        $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        if (!method_exists($this, $getter)) {
            $this->exception(
                'The option "' . $key . '" does not '
                . 'have a matching ' . $getter . ' getter method '
                . 'which must be defined'
            );
        }
        return $this->{$getter}();
    }

    public function __unset($key)
    {
        try {
            $this->__set($key, null);
        } catch (\InvalidArgumentException $e) {
            $this->exception(
                'The class property $' . $key . ' cannot be unset as'
                    . ' NULL is an invalid value for it',
                0,
                $e
            );
        }
    }
}
