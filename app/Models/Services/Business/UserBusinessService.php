<?php
namespace App\Models\Services\Business;

use App\Models\Services\Data\UserDataService;

class UserBusinessService
{
    
    private $dbService;
    
    function __construct()
    {
        $this->dbService = new UserDataService();
    }
    
    function newUser($newUser)
    {
        return $this->dbService->create($newUser);
    }
    
    function getUser($id)
    {
        return $this->dbService->read($id);
    }
    
    function getAllUsers()
    {
        return $this->dbService->readAll();
    }
    
    function editUser($updatedUser)
    {
        return $this->dbService->update($updatedUser);
    }
    
    function deleteUser($id)
    {
        return $this->dbService->delete($id);
    }
    
    function findByFirstName($n)
    {
        return $this->dbService->findByFirstName($n);
    }
    
    function login($username, $password)
    {
        return $this->dbService->login($username, $password);
    }
    
    function logout()
    {
        //not complete
    }
}



 
 
