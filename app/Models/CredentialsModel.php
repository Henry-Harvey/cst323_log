<?php
namespace App\Models;
//This model is for containing the information for people to log in to the site

// product class
class CredentialsModel
{

    private $id;

    private $username;

    private $password;

    // not in the constructor
    private $suspended;

    function __construct($id, $username, $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSuspended()
    {
        return $this->suspended;
    }

    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;
    }
}