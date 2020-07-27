<?php

namespace App\Models\User;

use Lib\Cache\RedisHash;

class UserCache extends \App\Provider\CacheHandler
{

    protected $cacheKey = 'user:%d';

    private static $instances;

    private static $cacheFileds = [
        'user_id',
        'name',
        'tel',
        'email',
        'sex'
    ];

    private $instance;

    private $userHashmap;

    public function __construct(\Interfaces\CacheProvider $cacheProvider, $user_id)
    {
        $this->cacheKey = sprintf($this->cacheKey, $user_id);

        parent::__construct($cacheProvider, $this->cacheKey);
        $this->userHashmap = new RedisHash($this->getCacheClient(), $this->cacheKey);

        $this->instance = $this;
        return $this;
    }

    static function getInstance($user_id)
    {

        if (isset(self::$instances[$user_id]))
            return self::$instances[$user_id];


        $instance = new self(\Lib\Redis::getInstance(), $user_id);

        self::$instances[$user_id] = $instance;

        return self::$instances[$user_id];
    }

    public function addUserCache(array $user)
    {

        //只取需要的


        $c_user = [];
        foreach (self::$cacheFileds as $param) {
            $c_user[$param] = $user[$param];
        }
        $this->userHashmap->hmset($c_user);

        return $c_user;

    }

    public function getUserCacheField($field)
    {

        if (!is_numeric(array_search($field, self::$cacheFileds))) {

            return null;
        }

        return $this->userHashmap->hget($field);
    }

    public function getUserCacheFields($i_fields)
    {

        $fields = [];
        foreach (self::$cacheFileds as $field) {
            if (is_numeric(array_search($field, $i_fields)))
                $fields[] = $field;
        }

        $cache = $this->userHashmap->hmget($fields);

        $ret = [];

        foreach ($fields as $key => $value) {
            $ret[$value] = $cache[$key];
        }


        return $ret ?? [] ;

    }

    public function updateUserCache($filed, $value)
    {
        if (!is_numeric(array_search($filed, self::$cacheFileds))) {
            return false;
        }
        return $this->userHashmap->hset($filed, $value);

    }

}
