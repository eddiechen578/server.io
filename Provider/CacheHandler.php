<?php


namespace Provider;


class CacheHandler
{
    protected $cacheKey;

    protected $cacheProvider;

    public function __construct(\Interfaces\CacheProvider $cacheProvider, $cacheKey)
    {
        $this->cacheKey = $cacheKey;

        $this->cacheProvider = $cacheProvider;
    }

    public function getCacheClient()
    {
        return $this->cacheProvider::getRedisClient();
    }
}
