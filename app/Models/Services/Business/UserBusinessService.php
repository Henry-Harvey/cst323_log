<?php
namespace App\Models\Services\Business;

use App\Models\Services\Data\UserDataService;
use Illuminate\Support\Facades\Log;
use App\Models\Utility\DatabaseModel;

class UserBusinessService
{

    /**
     * Takes in a user model to be created
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the checkUsername() method from the ds
     * If it returns 0, calls the create() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the create() method
     *
     * @param
     *            newUser user to be registered
     * @return {@link Integer} number of rows affected
     */
    function register($newUser)
    {
        Log::info("Entering UserBusinessService.register()");

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $flag = 0;
        $ds = new UserDataService($db);
        if ($ds->checkUsername($newUser->getCredentials()) == 0) {
            $flag = $ds->create($newUser);
        }

        $db = null;

        Log::info("Exiting UserBusinessService.register() with " . $flag);
        return $flag;
    }

    /**
     * Takes in a user model to search for
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the read() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the read() method
     *
     * @param
     *            searchUser user to be searched for
     * @return {@link UserModel} user that was found
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
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the readAll() method from the ds
     * Sets the database equal to null
     * Returns the result of the readAll() method
     *
     * @return {@link Array} array of users found
     */
    function getAllUsers()
    {
        Log::info("Entering UserBusinessService.getAllUsers()");

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new UserDataService($db);
        $flag = $ds->readAll();

        $db = null;

        Log::info("Exiting UserBusinessService.getAllUsers() with all users");
        return $flag;
    }

    /**
     * Takes in a user model to update
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the update() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the update() method
     *
     * @param
     *            updatedUser user to be updated
     * @return {@link Integer} number of rows affected
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
     * Takes in a user model to delete
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the delete() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the delete() method
     *
     * @param
     *            deleteUser user to be deleted
     * @return {@link Integer} number of rows affected
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
     * Takes in a credentials model
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the authenticate() method from the ds with the credentials model as the parameter
     * Sets the database equal to null
     * Returns the result of the authenticate() method
     *
     * @param
     *            loginCredentials credentials to authenticate
     * @return {@link UserModel} user that was authenticated
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

    /**
     * Takes in a user model to search for
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the readByName() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the readByName() method
     *
     * @param
     *            searchUser user to be searched for
     * @return {@link Array} array of Users found
     */
    function findByName($searchUser)
    {
        Log::info("Entering UserBusinessService.findByName()");

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new UserDataService($db);
        $flag = $ds->readByName($searchUser);

        $db = null;

        Log::info("Exiting UserBusinessService.findByName() with " . $flag);
        return $flag;
    }

    /**
     * Takes in a user model to toggle
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the toggleSuspend() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the toggleSuspend() method
     *
     * @param
     *            searchUser user to be toggled
     * @return {@link Array} number of rows affected
     */
    function toggleSuspendUser($toggleSuspendUser)
    {
        Log::info("Entering UserBusinessService.toggleSuspendUser()");

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new UserDataService($db);
        $flag = $ds->toggleSuspend($toggleSuspendUser);

        $db = null;

        Log::info("Exiting UserBusinessService.toggleSuspendUser() with " . $flag);
        return $flag;
    }
}



 
 
