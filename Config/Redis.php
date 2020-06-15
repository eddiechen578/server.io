<?php


namespace Config;


class Redis
{
    const
        cluster = 'database',
        host = '127.0.0.1',
        password = '@--redis--@',
        port = 6379,
        service = '--service--',
        pub_channel = 'web_to_chat',
        msq_queue = 'msg_queue';

    public static
        $ssl = false,
        $switch = true;

    static function getCluster()
    {
        return $_SERVER['REDIS_CLUSTER'] ?? self::cluster;
    }

    static function getServer()
    {
        return self::getSettings()['server'];
    }

    static function getSettings()
    {
        $settings = [
            'database' => [
                'replication' => true,
                'server' => [
                   'host' => 'localhost',
                   'port' => self::port,
                   'scheme' => self::$ssl ? 'tls' : 'tcp',
                   'ssl' => null,
                   'persistent' => true
                ],
                'service' => self::service,
                'password' => self::password,
                'options' => [
                    'prefix' => 'vue_'
                ]
            ]
        ];

        return $settings[self::cluster];
    }
}
