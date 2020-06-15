<?php


namespace Lib\Cache;


class RedisHash
{
    protected $redisClient;
    protected $key;

    public function __construct(\Predis\Client $redisClient, $key)
    {
        $this->redisClient = $redisClient;
        $this->key = $key;
    }

    function getCacheKey()
    {
        return $this->key;
    }

    function hdel(array $fields)
    {
        return $this->redisClient->hdel($this->key, $fields);
    }

    function hexists($field)
    {
        return $this->redisClient->hexists($this->key, $field);
    }

    function hget($field)
    {
        return $this->redisClient->hget($this->key, $field);
    }

    function hincrby($field, int $increment)
    {
        return $this->redisClient->hincrby($this->key, $field, $increment);
    }

    function hincrbyfloat($field, float $increment)
    {
        return $this->redisClient->hincrbyfloat($this->key, $field, $increment);
    }

    function hgetall()
    {
        return $this->redisClient->hgetall($this->key);
    }

    function hkeys()
    {
        return $this->redisClient->hkeys($this->key);
    }

    function hlen()
    {
        return $this->redisClient->hlen($this->key);
    }

    function hmget(array $fields)
    {
        return $this->redisClient->hmget($this->key, $fields);
    }

    //NOTE：注意key不要有冒號 不然predis會出錯
    function hmset(array $dictionary)
    {
        return $this->redisClient->hmset($this->key, $dictionary);
    }

    function hset($field, $value)
    {
        return $this->redisClient->hset($this->key, $field, $value);
    }

    function hsetnx($field, $value)
    {
        return $this->redisClient->hsetnx($this->key, $field, $value);
    }

    function hvals()
    {
        return $this->redisClient->hvals($this->key);
    }
}
