<?php


namespace App\Models\User\Request\Post;


class indexAction
{
    private $user_id;


    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     *
     * @param int $user_id
     */

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    private $name ;


    public function getName()
    {
        return $this->name;
    }
    /**
     *
     * @param string $name
     */

    public function setName($name)
    {
        $this->name = $name;
    }

    private $tel;

    private $email;

    private $password;

    private $sex;

    private $lv;

    private $time;
}
