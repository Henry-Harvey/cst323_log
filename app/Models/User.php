<?php

namespace App\Models;

// product class
class User{
    
    private $id;
    private $first_name;
    private $last_name;
    private $username;
    private $password;
    private $role;
    
    function __construct($id, $first_name, $last_name, $username, $password, $role){
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getFirst_name()
    {
        return $this->first_name;
    }
    
    public function getLast_name()
    {
        return $this->last_name;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }
    
    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function setRole($role)
    {
        $this->role = $role;
    }
    
}