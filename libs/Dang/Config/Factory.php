<?php

namespace Dang\Config;

class Factory
{
    /**
     * Read a config from a file.
     *
     * @param  string  $filename
     * @param  boolean $returnConfigObject
     * @return Config
     * @throws Dang\Exception\RuntimeException
     */
    public static function fromFile($filename)
    {
        $pathinfo = pathinfo($filename);

        if (!isset($pathinfo['extension'])) {
            throw new \Dang\Exception\RuntimeException(sprintf(
                'Filename "%s" is missing an extension and cannot be auto-detected',
                $filename
            ));
        }
        
        $extension = strtolower($pathinfo['extension']);
        if ($extension !== 'php') {
            throw new \Dang\Exception\RuntimeException(sprintf(
                'Unsupported config file extension: .%s',
                $pathinfo['extension']
            ));
        } 
        
        if (!is_file($filename) || !is_readable($filename)) {
            throw new \Dang\Exception\RuntimeException(sprintf(
                "File '%s' doesn't exist or not readable",
                $filename
            ));
        }
        
        $config = include $filename;

        return new Config($config);
    }
}
