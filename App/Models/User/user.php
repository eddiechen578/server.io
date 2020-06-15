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
}
