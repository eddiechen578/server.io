<?php

namespace App\Models\Post;

use \App\Models\Post\DB;

class post
{

    public static function getData()
    {
        $post = new DB();

        $data = $post->select('post_id')
                     ->get();
        dd($data);exit;
        return $data;
    }

}
