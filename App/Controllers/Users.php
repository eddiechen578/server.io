<?php


namespace App\Controllers;


class Users extends \Core\Controller
{

    public function indexAction($request, \Interfaces\Services\ServiceResultInterface $result)
    {
        dd($request->getName());exit;
    }
}
