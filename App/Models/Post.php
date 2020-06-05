<?php

namespace App\Models;

use Config\Database;
/**
 * Post model
 *
 * PHP version 7.3
 */
class Post extends \Core\Model
{
    public static
        $database = Database::app,
        $table = Database::app . '.posts';

    /**
     * Get all the posts as an associative array
     *
     * @return array
     */
    public function getData()
    {
       dd($this->getAll());

    }
}
