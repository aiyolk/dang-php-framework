<?php

namespace Dang;

use Memcache as MemcacheResource;

class Dmemcache
{
    protected $memcache;

    public function __construct()
    {
        $servers = \Dang\Quick::config("memcache");

        $memcache = new MemcacheResource();
        
        for($i=0;$i<count($servers);$i++){
        	$config = $servers[$i];
        	$memcache->addServer($config->host, $config->port);
        }

        $this->memcache = $memcache;
    }

    public function getItem(& $normalizedKey, & $success = null, & $flags = null)
    {
        $memc = $this->memcache;

        if (func_num_args() > 2) {
            $result = $memc->get($normalizedKey, $flags);
        } else {
            $result = $memc->get($normalizedKey);
        }
       
        $success = true;
        if ($result === false || $result === null) {
            $success = false;
        }

        return $result;
    }

    public function delItem($normalizedKey)
    {
        $memc = $this->memcache;

        if (!$memc->delete($normalizedKey)) {
            return false;
        }

        return true;
    }

    public function setItem(& $normalizedKey, & $value, $expiration = 0)
    {
        $memc = $this->memcache;
        $flag = MEMCACHE_COMPRESSED;
        if (!$memc->set($normalizedKey, $value, $flag, $expiration)) {
            return false;
        }

        return true;
    }

    public function incrementItem(& $normalizedKey, $offset = 1)
    {
        $memc = $this->memcache;

        if (!$memc->increment($normalizedKey, $offset)) {
            $memc->set($normalizedKey, $offset);
        }

        return true;
    }

}

