<?php

namespace Dang;

use Memcached as MemcachedResource;

class Dmemcached
{
    protected $memcached;

    public function __construct()
    {
        $config = \Dang\Quick::config("cache");

        $memcached = new MemcachedResource('memcached_pool');
        $memcached->setOption(MemcachedResource::OPT_COMPRESSION, true);
        $memcached->setOption(MemcachedResource::OPT_DISTRIBUTION, MemcachedResource::DISTRIBUTION_CONSISTENT);
        $memcached->setOption(MemcachedResource::OPT_LIBKETAMA_COMPATIBLE, true);
        $memcached->addServers($config->memcached->servers->toArray());

        $this->memcached = $memcached;
    }

    public function getItem(& $normalizedKey, & $success = null, & $casToken = null)
    {
        $memc = $this->memcached;

        if (func_num_args() > 2) {
            $result = $memc->get($normalizedKey, null, $casToken);
        } else {
            $result = $memc->get($normalizedKey);
        }

        $success = true;
        if ($result === false || $result === null) {
            $rsCode = $memc->getResultCode();
            if ($rsCode == MemcachedResource::RES_NOTFOUND) {
                $result = null;
                $success = false;
            } elseif ($rsCode) {
                $success = false;
            }
        }

        return $result;
    }

    public function setItem(& $normalizedKey, & $value, $expiration=null)
    {
        $memc = $this->memcached;

        if($expiration == null){
            $config = \Dang\Quick::config("cache");
            $expiration = $config->memcached->expirationDefault;
        }

        $expiration = time() + $expiration;
        if (!$memc->set($normalizedKey, $value, $expiration)) {
            return false;
        }

        return true;
    }

}

