<?php

namespace App\Models;

// product class
class CredentialsModel{
    
    private $id;
    private $username;
    private $password;
     
    function __construct($id, $username, $password){
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
    
}