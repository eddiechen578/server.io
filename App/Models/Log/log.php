<?php

namespace App\Models\Log;

use App\Models\Log\DB;

class log
{
    static private
        $break = false,
        $id;

    private static function createLogTable()
    {
//        $string0 = date('Ymd', strtotime('+1 day'));
        $string0 = date('Ymd');
        DB::getDB()->exec(
            "CREATE TABLE " . DB::$database . "." . $string0 . " (
                    `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `user_id` int(10) unsigned DEFAULT NULL,
                    `session_id` varchar(32) DEFAULT NULL,
                    `server` text,
                    `get` text,
                    `post` mediumtext,
                    `input` mediumtext,
                    `session` text,
                    `cookie` text,
                    `return` text,
                    `exception` text,
                    `error` text,
                    `headers` text,
                    `ip` varbinary(16) NULL,
                    `latitude` decimal(10,7) DEFAULT NULL,
                    `longitude` decimal(10,7) DEFAULT NULL,
                    `coordinate_return` text,
                    `runtime` float(6,3)  DEFAULT '0.000',
                    `inserttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`log_id`),
                    KEY `user_id` (`user_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        return true;
    }


    private static function checkTableExist()
    {
        $tables = DB::getDB()->fetchColumn("SHOW TABLES FROM " . DB::$database . " LIKE " . DB::quote(str_replace(DB::$database . '.', '', DB::$table)));

        if(!$tables) $tables = self::createLogTable();

        return $tables ? true : false;
    }

    static function getId()
    {
        if (self::$id === null && self::checkTableExist()) {

            $input = trim(file_get_contents('php://input'));

            $server = [];

            $session = '';

            self::$id = (new DB)
                ->insert([
                    'cookie' => null,
                    '`get`' =>  null,
                    'headers' => null,
                    'input' => ($input === '') ? null : $input,
                    'ip' => '',
                    'post' => null,
                    'runtime' => null,
                    '`server`' => null,
                    '`session`' => null,
                    'session_id' => null,
                    'user_id' =>  null,
                ]);
        }

        return self::$id;
    }

    static function setLog(array $param = null)
    {

        if (self::checkTableExist() && !self::$break) {

            self::$break = true;

            $DatabaseLog = (new DB())
                ->select([
                    'error',
                    'exception',
                    '`return`',
                ])
                ->where('log_id', '=', self::getId())
                ->fetch();

            $update = [];

            if (isset($param['error'])) {
                $update['error'] = (trim($DatabaseLog['error']) === '') ? $param['error'] : $DatabaseLog['error'] . "\r\n" . $param['error'];
            }

            if (isset($param['exception'])) {
                $update['exception'] = (trim($DatabaseLog['exception']) === '') ? $param['exception'] : $DatabaseLog['exception'] . "\r\n" . $param['exception'];
            }

            if (isset($param['return'])) {
                $update['`return`'] = $param['return'];
            }

            (new DB())
                ->where('log_id', '=', self::getId())
                ->update(array_merge(
                        [
                            'runtime' => 0
                        ],
                        $update
                    )
                );

        }
    }

    static function setException($level, $message)
    {
        $Exception = (new \Lib\Exception())
            ->setLevel($level)
            ->setMessage($message);

        self::setLog([
            'exception' => $Exception->getTraceString(),
        ]);

        $Exception->output();
    }
}
