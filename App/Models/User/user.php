<?php

namespace App\Models\User;

use App\Models\User\UserCache;
use App\Models\User\DB;

class user
{
        static function getUserFieldsFromCache($user_id, $fields)
        {

            $c_value = UserCache::getInstance($user_id)->getUserCacheFields($fields);

            $check = $c_value[$fields[0]];

            if (empty($check)) {
                $user = new DB();

                $d_user = $user->where('user_id', '=', $user_id)
                               ->fetch('array');

                if($d_user){
                   $c_value =  UserCache::getInstance($user_id)->addUserCache($d_user);
                }
            }

            return $c_value;
        }

        static function insert($insert)
        {
            $user = new DB();

            $user_id = $user->insert($insert);

            return $user_id;
        }

        static function __validateOfIndexAction(&$requestObject, \Interfaces\Services\ServiceResultInterface &$serviceResult)
        {

            if(strlen($requestObject->getName()) == 0){
                $serviceResult->addInputError("name", "name is required");
            }

            if(strlen($requestObject->getTel()) == 0){
                $serviceResult->addInputError("tel", "tel is required");
            }

            if(strlen($requestObject->getEmail()) == 0){
                $serviceResult->addInputError("email", "email is required");
            }

            if(strlen($requestObject->getPassword()) == 0){
                $serviceResult->addInputError("password", "password is required");
            }

            if(strlen($requestObject->getPassword()) > 0){
                $pwd = $requestObject->getPassword();

                $requestObject->setPassword(password_hash($pwd, PASSWORD_DEFAULT));
            }

            if(strlen($requestObject->getSex()) == 0){
                $serviceResult->addInputError("sex", "sex is required");
            }

            if(strlen($requestObject->getLv()) == 0){
                $serviceResult->addInputError("lv", "lv is required");
            }

        }
}
