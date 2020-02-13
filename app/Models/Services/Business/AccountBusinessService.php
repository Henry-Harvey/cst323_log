<?php
namespace App\Models\Services\Business;

use App\Models\Services\Data\UserDataService;
use App\Models\Utility\DatabaseModel;
use Illuminate\Support\Facades\Log;
use App\Models\Services\Data\CredentialsDataService;
use App\Models\Objects\CredentialsModel;

class AccountBusinessService
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
     * @return {@link Integer} number of row(s) affected
     */
    function register($newUser)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUser);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $db->beginTransaction();

        $credentialsDS = new CredentialsDataService($db);
        $userDS = new UserDataService($db);

        $flag = $credentialsDS->create($newUser->getCredentials());

        if ($flag == -1) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $insertId = $flag;

        $newUser->setCredentials_id($insertId);

        $flag2 = $userDS->create($newUser);

        if ($flag2 != 1) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }

        $db->commit();

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
        return $flag2;
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
    function getUser($partialUser)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialUser);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $db->beginTransaction();

        $credentialsDS = new CredentialsDataService($db);
        $userDS = new UserDataService($db);

        $flag = $userDS->read($partialUser);

        if (is_int($flag)) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $user = $flag;

        $partialCredentials = new CredentialsModel($user->getCredentials_id(), "", "", 0);

        $flag2 = $credentialsDS->read($partialCredentials);

        if (is_int($flag2)) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }

        $credentials = $flag2;

        $user->setCredentials($credentials);

        $db->commit();

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
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
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $db->beginTransaction();

        $credentialsDS = new CredentialsDataService($db);
        $userDS = new UserDataService($db);

        $flag = $userDS->readAll();

        if (is_int($flag)) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $users_array = $flag;

        foreach ($users_array as $user) {
            $partialCredentials = new CredentialsModel($user->getCredentials_id(), "", "", 0);
            $flag2 = $credentialsDS->read($partialCredentials);

            if (is_int($flag2)) {
                Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
                return $flag2;
            }

            $credentials = $flag2;

            $user->setCredentials($credentials);
        }

        $db->commit();

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with UserModel array");
        return $users_array;
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
     * @return {@link Integer} number of row(s) affected
     */
    function editUser($updatedUser)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedUser);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $userDS = new UserDataService($db);

        $flag = $userDS->update($updatedUser);

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
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
     * @return {@link Integer} number of row(s) affected
     */
    function remove($deleteUser)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $deleteUser);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $db->beginTransaction();

        $credentialsDS = new CredentialsDataService($db);
        $userDS = new UserDataService($db);

        $flag = $userDS->delete($deleteUser);
        $flag2 = $credentialsDS->delete($deleteUser->getCredentials());
        
        if ($flag == 1 && $flag2 == 1) {
            $db->commit();
            $flag3 = 1;
        } else {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $flag3 = 0;
        }

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag3);
        return $flag3;
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
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $loginCredentials);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new CredentialsDataService($db);
        $flag = $ds->authenticate($loginCredentials);

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
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
     * @return {@link Array} number of row(s) affected
     */
    function toggleSuspension($user)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new CredentialsDataService($db);

        $credentials = $user->getCredentials();

        // flip suspended
        if ($credentials->getSuspended() != 0) {
            $credentials->setSuspended(0);
        } else {
            $credentials->setSuspended(1);
        }

        // update user
        $flag = $ds->update($credentials);

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}



 
 
