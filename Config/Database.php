<?php

namespace Config;

/**
 * Application configuration
 *
 * PHP version 7.3
 */
class Database
{
    const _prefix = 'vue_',
          app = self::_prefix . 'app',
          log = self::_prefix . 'log';

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'eddie';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '1111';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = false;

    static function settings()
    {

        $setting = [

            self::app => [
                'HOST' => self::DB_HOST,
                'DSN' => 'mysql:dbname=' . self::app . ';host=' . self::DB_HOST,
                'USER' => self::DB_USER,
                'PASSWORD' => self::DB_PASSWORD,
            ],

            self::log => [
                'HOST' => self::DB_HOST,
                'DSN' => 'mysql:dbname=' . self::log . ';host=' . self::DB_HOST,
                'USER' => self::DB_USER,
                'PASSWORD' => self::DB_PASSWORD,
            ]
        ];

        return $setting;
    }
}
