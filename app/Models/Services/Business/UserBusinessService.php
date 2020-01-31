<?php
namespace App\Models\Services\Business;

use App\Models\Services\Data\UserDataService;
use Illuminate\Support\Facades\Log;
use App\Models\Utility\DatabaseModel;

class UserBusinessService
{
    
    /**
     * Takes in a user
     * Uses the data service's register() method and returns its result
     *
     * @param newUser		user to be registered
     * @return {@link Boolean}		a boolean to show if successful
     */
    function register($newCredentials, $newUser)
    {
        Log::info("Entering UserBusinessService.register()");
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserDataService($db);
        $flag = $ds->create($newCredentials, $newUser);
        
        $db = null;
        
        Log::info("Exiting UserBusinessService.register() with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user id
     * Uses the data service's read() method and returns its result
     *
     * @param id	an id of the user to return
     * @return {@link User}}		the user that was found
     */
    function getUser($searchUser)
    {
        Log::info("Entering UserBusinessService.getUser()");
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserDataService($db);
        $flag = $ds->read($searchUser);
        
        $db = null;
        
        Log::info("Exiting UserBusinessService.getAllUsers() with " . $flag);
        return $flag;
    }
    
    /**
     * Uses the data service's readAll() method and returns its result
     *
     * @return {@link List}		the user list that was found
     */
    function getAllUsers()
    {
        Log::info("Entering UserBusinessService.getAllUsers()");
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserDataService($db);
        $flag = $ds->readAll();
        
        $db = null;
        
        Log::info("Exiting UserBusinessService.getAllUsers() with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user
     * Uses the data service's update() method and returns its result
     *
     * @param updatedUser	a user to be updated
     * @return {@link Boolean}		a boolean to show if successful
     */
    function editUser($updatedUser)
    {
        Log::info("Entering UserBusinessService.editUser()");
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserDataService($db);
        $flag = $ds->update($updatedUser);
        
        $db = null;
        
        Log::info("Exiting UserBusinessService.editUser() with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user id
     * Uses the data service's delete() method and returns its result
     *
     * @param id		an id of the user to delete
     * @return {@link Boolean}		a boolean to show if successful
     */
    function remove($deleteUser)
    {
        Log::info("Entering UserBusinessService.delete()");
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserDataService($db);
        $flag = $ds->delete($deleteUser);
        
        $db = null;
        
        Log::info("Exiting UserBusinessService.delete() with " . $flag);
        return $flag;
    }
    
    /**
     * Uses the data service's findByFirstName() method and returns its result
     *
     * @param n	    the first name of the users to return
     * @return {@link List}		the user list that was found
     */
    function findByName($searchUser)
    {
        Log::info("Entering UserBusinessService.findByName()");
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserDataService($db);
        $flag = $ds->findByName($searchUser);
        
        $db = null;
        
        Log::info("Exiting UserBusinessService.findByName() with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in username and password strings
     * Uses the data service's login() method and returns its result
     *
     * @param username, password		credentials used for login
     * @return {@link User}		the user that was logged in
     */
    function login($loginCredentials)
    {
        Log::info("Entering UserBusinessService.login()");
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserDataService($db);
        $flag = $ds->authenticate($loginCredentials);
        
        $db = null;
        
        Log::info("Exiting UserBusinessService.login() with " . $flag);
        return $flag;
    }
    
    function logout()
    {
        return null;
    }
}



 
 
