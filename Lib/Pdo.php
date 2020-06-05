<?php

namespace Lib;

class pdo
{
    private $pdo = null;

    public function  __construct(array $param)
    {
        try {
            $this->pdo = new \PDO(
                $param['DSN'],
                $param['USER'],
                $param['PASSWORD'],
                [
                    \PDO::ATTR_EMULATE_PREPARES => false,//2015-11-04 Lion: 依資料型態回傳(註一)
                    \PDO::ATTR_PERSISTENT => false,//2018-01-19 Lion: 持久連接無法有效地建立事務處理, 參考 https://stackoverflow.com/questions/3765925/persistent-vs-non-persistent-which-should-i-use
                    \PDO::ATTR_STRINGIFY_FETCHES => false,//2015-11-04 Lion: 依資料型態回傳(註一)
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
                ]
            );
        }catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    function commit()
    {
        $this->pdo->commit();
    }

    function errorCode()
    {
        return $this->pdo->errorCode();
    }

    function errorInfo()
    {
        return $this->pdo->errorInfo();
    }

    function exec($sql)
    {
        $return = $this->pdo->exec($sql);

        if($this->errorCode() !== '00000'){

        }
    }

    function fetchColumn($sql)
    {
        $return = false;//2017-10-20 Lion: fetchColumn 在沒找到東西時是回傳 boolean false, 故預設 return boolean false

        $query = $this->query($sql);

        if (is_object($query)) {
            $return = $query->fetchColumn();
        }

        return $return;
    }

    function query($sql)
    {
        $result = $this->pdo->query($sql);

        return $result;
    }

}
