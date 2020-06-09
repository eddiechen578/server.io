<?php


namespace App\Models\Log;

use Config\Database;

class DB extends \Core\Model
{
    public static
        $database = Database::log,
        $table;


}
