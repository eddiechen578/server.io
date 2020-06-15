<?php


namespace Lib;

use \App\Models\Log\log;

class Redis implements \Interfaces\CacheProvider
{
    private static
        $connection_pool = [],
        $usable_pool = [];

    private static $self = null;

    static function getInstance()
    {
        if (!isset(self::$self))
            self::$self = new self();

        return self::$self;
    }

    static function flushAll(): bool
    {
        if(!self::usable()) goto _return;

        $bool = self::getRedis()->flushAll() == 'OK';

        _return:

        return $bool ?? false;
    }

    static function flushDb(): bool
    {
        if(!self::usable()) goto _return;

        $bool = self::getRedis()->flushDb() == 'OK';

        _return:

            return $bool ?? false;
    }

    public static function getRedis()
    {
        $cluster = \Config\Redis::getCluster();

        $init = function () use ($cluster){
            $server = \Config\Redis::getServer();
            $setting = \Config\Redis::getSettings();

            $params = [];

            $params = [
                'host' => $server['host'],
                'password' => null,
                'port' => $server['port'],
                'scheme' => $server['scheme']
            ];

            $options = $setting['options'];

            if (phpversion('redis') >= '3.0') {
                $options['cluster'] = 'redis';
            }

            try {

                self::$connection_pool[$cluster] = new \Predis\Client($params?? null, $options?? null );

                self::$connection_pool[$cluster]->connect();

                self::$usable_pool[$cluster] = true;


            } catch (\Predis\Connection\ConnectionException $e) {

                log::setException(\Lib\Exception::LEVEL_NOTICE, $e->getMessage());

                self::$usable_pool[$cluster] = false;
            }
        };

        if(!isset(self::$connection_pool[$cluster])) $init();

        return self::$connection_pool[$cluster];
    }

    public static function getRedisClient()
    {
        return self::getRedis();
    }

    static function usable(): bool
    {
        $bool = false;

        if(\Config\Redis::$switch){
            $i = self::getRedis();

            if(self::$usable_pool[\Config\Redis::getCluster()]){
                $bool = $i->ping() == 'PONG';
            }
        }

        return $bool;
    }
}
