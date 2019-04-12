<?php

namespace Raw\CustomerPrice\Import\Communication\Service;

class Redis
{
    private $redis;


    private $cache = [];

    private $i = 0;

    public function __construct()
    {
        $this->redis = new \Redis;
        $this->redis->connect('redis');
        $this->redis->setOption(\Redis::OPT_PREFIX, 'price:');
    }

    public function set($key, $value)
    {
        $this->cache[$key] = $value;

        if($this->i > 500) {
            $this->redis->mset($this->cache);
            $this->cache = [];
            $this->i = 0;
        }
    }

    public function get($customerNumber, $productNumber)
    {
        return $this->redis->get($customerNumber  . ':' . $productNumber);
    }
}