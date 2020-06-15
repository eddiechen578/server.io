<?php


namespace App\Models\User;

use Config\Database;

class DB extends \Core\Model
{
    public static
        $database = Database::app,
        $table = Database::app . '.users';
}
