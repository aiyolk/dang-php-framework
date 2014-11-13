<?php

namespace Dang;

use Memcached as MemcachedResource;

class Dmemcached
{
    protected $memcached;

    public function __construct()
    {
        $config = \Dang\Quick::config("memcached");
		
        $memcached = new MemcachedResource("ocs");

        //检查是否已经生成了长连接
        if (count($memcached->getServerList()) == 0){
	        $memcached->setOption(MemcachedResource::OPT_COMPRESSION, true);
	        $memcached->setOption(MemcachedResource::OPT_DISTRIBUTION, MemcachedResource::DISTRIBUTION_CONSISTENT);
	        $memcached->setOption(MemcachedResource::OPT_LIBKETAMA_COMPATIBLE, true);
	        	
	        $memcached->addServer($config->host, $config->port);
	        
	        //支持sasl功能
	        if(isset($config->username) && $config->username != ""){
	        	$memcached->setOption(MemcachedResource::OPT_BINARY_PROTOCOL, true);
	        	$memcached->setSaslAuthData($config->username, $config->password);
	        }
        }
        
        $this->memcached = $memcached;
    }

    public function getItems($keys, & $casToken = null)
    {
    	$memc = $this->memcached;
    	
    	$result = $memc->getMulti($keys, $casToken, MemcachedResource::GET_PRESERVE_ORDER);
    	
    	return $result;
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

    public function delItem($normalizedKey)
    {
        $memc = $this->memcached;

        if (!$memc->delete($normalizedKey)) {
            return false;
        }

        return true;
    }

    public function setItem(& $normalizedKey, & $value, $expiration = 0)
    {
        $memc = $this->memcached;

        if (!$memc->set($normalizedKey, $value, $expiration)) {
            return false;
        }

        return true;
    }

    public function incrementItem(& $normalizedKey, $offset = 1, $expiration = 0)
    {
        $memc = $this->memcached;

        if (!$memc->increment($normalizedKey, $offset)) {
            $memc->set($normalizedKey, $offset, $expiration);
        }

        return true;
    }

    public function flush()
    {
    	$memc = $this->memcached;
    	
    	return $memc->flush();
    }
}

