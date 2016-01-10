<?php

namespace Dang;

use Memcached as MemcachedResource;

class Dmemcached
{
    protected $memcached;
    
    private $_debug;

    public function __construct()
    {
        $config = \Dang\Quick::config("memcached");
		
        $memcached = new MemcachedResource("ocs");

        //检查是否已经生成了长连接
        if (count($memcached->getServerList()) == 0){
	        $memcached->setOption(MemcachedResource::OPT_COMPRESSION, true);
	        $memcached->setOption(MemcachedResource::OPT_DISTRIBUTION, MemcachedResource::DISTRIBUTION_CONSISTENT);
	        $memcached->setOption(MemcachedResource::OPT_LIBKETAMA_COMPATIBLE, true);
	        $memcached->setOption(MemcachedResource::OPT_BINARY_PROTOCOL, true);
	        
	        $memcached->addServer($config->host, $config->port);
	        
	        //支持sasl功能
	        if(isset($config->username) && $config->username != ""){
	        	$memcached->setSaslAuthData($config->username, $config->password);
	        }
        }
        
        $this->memcached = $memcached;
        
        $this->_debug = \Dang_Mvc_Request::instance()->getParam("debug", 0);
    }

    public function getItems($keys)
    {
    	$memc = $this->memcached;
    	
    	$result = $memc->getMulti($keys, $cas, MemcachedResource::GET_PRESERVE_ORDER);
    	
    	return $result;
    }
    
    /**
     * 可以 是一个Unix时间戳（自1970年1月1日起至失效时间的整型秒数），或者是一个从现在算起的以秒为单位的数字。
     * 对于后一种情况，这个 秒数不能超过60×60×24×30（30天时间的秒数）;
     * 如果失效的值大于这个值， 服务端会将其作为一个真实的Unix时间戳来处理而不是 自当前时间的偏移。
     * @param array $items
     * @param inter $expiration
     * @return bool
     */
    public function setItems($items, $expiration = 0){
        $memc = $this->memcached;
        
        $result = $memc->setMulti($items, $expiration);
        
        return $result;
    }
    
    public function getItem(& $normalizedKey, & $success = null)
    {
        \Zend\Debug\Debug::dump($normalizedKey, "Memcached key: ", $this->_debug);
        
        $memc = $this->memcached;

        $result = $memc->get($normalizedKey);
        
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
        
        \Zend\Debug\Debug::dump($result, "Memcached result: ", $this->_debug);

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

