<?php


namespace App\Models;

use Config\Database;

class Log extends \Core\Model
{
    public static
        $database = Database::log,
        $table;


}
