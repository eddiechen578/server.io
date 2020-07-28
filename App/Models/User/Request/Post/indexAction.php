<?php


namespace App\Models\User\Request\Post;


class indexAction
{

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

    public function getTel()
    {
        return $this->tel;
    }
    /**
     *
     * @param string $tel
     */

    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    private $email;

    public function getEmail()
    {
        return $this->email;
    }
    /**
     *
     * @param string $email
     */

    public function setEmail($email)
    {
        $this->email = $email;
    }

    private $password;

    public function getPassword()
    {
        return $this->password;
    }
    /**
     *
     * @param string $password
     */

    public function setPassword($password)
    {
        $this->password = $password;
    }

    private $sex;

    public function getSex()
    {
        return $this->sex;
    }
    /**
     *
     * @param int $sex
     */

    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    private $lv;

    public function getLv()
    {
        return $this->lv;
    }
    /**
     *
     * @param int $lv
     */

    public function setLv($lv)
    {
        $this->lv = $lv;
    }

    private $time;

    public function getTime()
    {
        return $this->time;
    }
    /**
     *
     * @param int $time
     */

    public function setTime($time)
    {
        $this->time = $time;
    }
}
