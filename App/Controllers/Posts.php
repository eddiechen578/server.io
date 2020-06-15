<?php

namespace App\Controllers;

use App\Models\Post\post;
use Lib\Redis;
use App\Models\User\user;
/**
 * Posts controller
 *
 * PHP version 7.3
 */
class Posts extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $fields = [
            'user_id',
            'name',
            'tel',
            'email',
            'sex'
        ];

        $data = user::getUserFieldsFromCache(37, $fields);
        dd($data);exit;
    }

    public function getByIdAction($id)
    {
        dd($id);exit;
    }

    /**
     * Show the add new page
     *
     * @return void
     */
    public function addNewAction()
    {
        echo 'Hello from the addNew action in the Posts controller!';
    }

    /**
     * Show the edit page
     *
     * @return void
     */
    public function editAction()
    {
        echo 'Hello from the edit action in the Posts controller!';
        echo '<p>Route parameters: <pre>' .
             htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
    }
}
