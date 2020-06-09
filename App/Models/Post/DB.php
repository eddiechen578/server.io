<?php


namespace App\Models\Post;

use Config\Database;

class DB extends \Core\Model
{
    public static
        $database = Database::app,
        $table = Database::app . '.posts';
}
