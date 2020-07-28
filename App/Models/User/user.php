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

        static function validate($requestObject, \Interfaces\Services\ServiceResultInterface &$serviceResult)
        {
            if(strlen($requestObject->getUser_id()) == 0){
                $serviceResult->addInputError("user_id", "user_id is required");
            }

            if(strlen($requestObject->getName()) == 0){
                $serviceResult->addInputError("name", "name is required");
            }
        }
}
