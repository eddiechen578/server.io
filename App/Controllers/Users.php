<?php


namespace App\Controllers;


class Users extends \Core\Controller
{

    public function indexAction($requestObject, \Interfaces\Services\ServiceResultInterface $result)
    {
        $insert = [
            'name' => $requestObject->getName(),
            'tel' => $requestObject->getTel(),
            'email' => $requestObject->getEmail(),
            'password' => $requestObject->getPassword(),
            'sex' => $requestObject->getSex(),
            'lv' => $requestObject->getLv()
        ];

        $getId = \App\Models\User\user::insert($insert);

        $result->setData("{'id' : '". $getId ."', statusMessage' : 'The user was added successfully'}");
    }
}
