<?php

namespace App\Services\Useful;

class UserData
{

    public $id;

    public $email;

    public $password;

    public function __construct($email = null, $password = null, $id = null)
    {
        $this->id       = $id;
        $this->email    = trim(strtolower($email));
        $this->password = trim($password);
        return $this;
    }

    /**
     *
     * @access public
     * @param  int    $id set id of user
     * @return class  App\Useful\UserData
     */
    public function setId($id = 0)
    {
        if ($id > 0)
        {
            $this->id = $id;
        }
        return $this;
    }

    /**
     *
     * @access public
     * @param  string $mail set e-mail
     * @return App\Useful\UserData
     */
    public function setEmail($email = null)
    {
        $this->email = trim(strtolower($email));
        return $this;
    }

    /**
     *
     * @access public
     * @param  string $password set password
     * @return App\Useful\UserData
     */
    public function setPassword($password = null)
    {
        $this->password = trim($password);
        return $this;
    }

}
