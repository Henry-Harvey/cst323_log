<?php
namespace App\Models\Services\Data;

use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;
use App\Models\Utility\DatabaseException;
use App\Models\UserModel;
use App\Models\CredentialsModel;

class UserDataService implements DataServiceInterface
{

    private $db = NULL;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     *
     * @see DataServiceInterface create
     */
    function create($newUser)
    {
        Log::info("Entering UserDataService.create()");
        try {
            $username = $newUser->getCredentials()->getUsername();
            $password = $newUser->getCredentials()->getPassword();

            $stmt = $this->db->prepare('INSERT INTO credentials
                                        (USERNAME, PASSWORD)
                                        VALUES (:username, :password)');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {

                $first_name = $newUser->getFirst_name();
                $last_name = $newUser->getLast_name();
                $location = $newUser->getLocation();
                $summary = $newUser->getSummary();
                $role = 0;
                $credentials_id = $this->db->lastInsertId();

                $stmt2 = $this->db->prepare('INSERT INTO users
                                            (FIRSTNAME, LASTNAME, LOCATION, SUMMARY, ROLE, CREDENTIALS_ID)
                                            VALUES (:first_name, :last_name, :location, :summary, :role, :credentials_id)');
                $stmt2->bindParam(':first_name', $first_name);
                $stmt2->bindParam(':last_name', $last_name);
                $stmt2->bindParam(':location', $location);
                $stmt2->bindParam(':summary', $summary);
                $stmt2->bindParam(':role', $role);
                $stmt2->bindParam(':credentials_id', $credentials_id);
                $stmt2->execute();
                Log::info("Exit SecurityDAO.create() with " . $stmt->rowCount() . " affected credentials row(s) and " . $stmt2->rowCount() . " affected user row(s)");
                return $stmt2->rowCount();
            }
            Log::info("Exit SecurityDAO.create() with null");
            return null;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     *
     * @see DataServiceInterface read
     */
    function read($searchUser)
    {
        Log::info("Entering UserDataService.read()");
        try {
            $id = $searchUser->getId();

            $stmt = $this->db->prepare('SELECT * FROM users
                                        WHERE ID = :id
                                        LIMIT 1');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $result["ID"];
                    $first_name = $result['FIRSTNAME'];
                    $last_name = $result['LASTNAME'];
                    $location = $result['LOCATION'];
                    $summary = $result['SUMMARY'];
                    $role = $result['ROLE'];
                    $credentials_id = $result['CREDENTIALS_ID'];
                }
                $user = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);
                $user = $this->setCredentials($user);

                Log::info("Exiting UserDataService.read() with " . $user . " (" . $stmt->rowCount() . " affected row(s))");
                return $user;
            }
            Log::info("Exiting UserDataService.read() with null");
            return null;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     *
     * @see DataServiceInterface readAll
     */
    function readAll()
    {
        Log::info("Entering UserDataService.readAll()");
        try {
            $stmt = $this->db->prepare('SELECT * FROM users');
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user_array = array();
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $result["ID"];
                    $first_name = $result['FIRSTNAME'];
                    $last_name = $result['LASTNAME'];
                    $location = $result['LOCATION'];
                    $summary = $result['SUMMARY'];
                    $role = $result['ROLE'];
                    $credentials_id = $result['CREDENTIALS_ID'];

                    $user = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);
                    $user = $this->setCredentials($user);
                    array_push($user_array, $user);
                }
                Log::info("Exiting UserDataService.readAll() with user array (" . $stmt->rowCount() . " row(s))");
                return $user_array;
            }
            Log::info("Exiting UserDataService.readAll() with null");
            return null;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     *
     * @see DataServiceInterface update
     */
    function update($updatedUser)
    {
        Log::info("Entering UserDataService.update()");
        try {
            $id = $updatedUser->getId();
            $firstname = $updatedUser->getFirst_name();
            $lastname = $updatedUser->getLast_name();
            $location = $updatedUser->getLocation();
            $summary = $updatedUser->getSummary();

            $stmt = $this->db->prepare('UPDATE users
                                        SET FIRSTNAME = :first_name, 
                                            LASTNAME = :last_name, 
                                            LOCATION = :location, 
                                            SUMMARY = :summary
                                        WHERE ID = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':first_name', $firstname);
            $stmt->bindParam(':last_name', $lastname);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':summary', $summary);
            $stmt->execute();

            Log::info("Exit SecurityDAO.update() with " . $stmt->rowCount());
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     *
     * @see DataServiceInterface delete
     */
    function delete($deleteUser)
    {
        Log::info("Entering UserDataService.delete()");
        try {
            $id = $deleteUser->getId();

            $stmt = $this->db->prepare('DELETE FROM users
                                        WHERE ID = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $credentials_id = $deleteUser->getCredentials_id();

                $stmt2 = $this->db->prepare('DELETE FROM credentials
                                        WHERE ID = :credentials_id');
                $stmt2->bindParam(':credentials_id', $credentials_id);
                $stmt2->execute();
                Log::info("Exit SecurityDAO.delete() with " . $stmt->rowCount() . " affected users row(s) and " . $stmt2->rowCount() . " affected credentials row(s)");
                return $stmt2->rowCount();
            }
            Log::info("Exit SecurityDAO.delete() with " . $stmt->rowCount() . " affected users row(s) and " . $stmt2->rowCount() . " affected credentials row(s)");
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Takes in a credentials model
     * Sets variables for each parameter of the object
     * Creates a SELECT sql statement from the database
     * Binds the parameters of the sql statement equal to the variables
     * Executes the sql statement
     * If no rows found, return null
     * If a row is found
     * Set a credentials_id equal to the selected row's id
     * Get and test the suspended column data
     * If it is not 0, return -1
     * Else, creates a second SELECT sql statement from the database
     * Binds the paramters of the sql statement equal to credentials_id
     * Executes the sql statement
     * If no rows found returns null
     * If rows were found, sets variables for all column data
     * Creates a user from the variables
     * Returns the user
     *
     * @param
     *            loginCredentials credentials to log in with
     * @return {@link User} user that was logged in
     */
    function authenticate($loginCredentials)
    {
        Log::info("Entering UserDataService.authenticate()");
        try {
            $username = $loginCredentials->getUsername();
            $password = $loginCredentials->getPassword();

            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE USERNAME = :username AND PASSWORD = :password');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $credentials_id = $result['ID'];
                    $suspended = $result['SUSPENDED'];
                }

                if ($suspended != 0) {
                    $msg = - 1;
                    return $msg;
                }

                $stmt2 = $this->db->prepare('SELECT * FROM users
                                            WHERE CREDENTIALS_ID = :credentials_id');
                $stmt2->bindParam(':credentials_id', $credentials_id);
                $stmt2->execute();

                if ($stmt2->rowCount() > 0) {
                    while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        $id = $result2["ID"];
                        $first_name = $result2['FIRSTNAME'];
                        $last_name = $result2['LASTNAME'];
                        $location = $result2['LOCATION'];
                        $summary = $result2['SUMMARY'];
                        $role = $result2['ROLE'];
                        $credentials_id = $result2['CREDENTIALS_ID'];
                        $user = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);
                    }
                    Log::info("Exiting UserDataService.authenticate() with " . $user . " (" . $stmt->rowCount() . " affected credentials row(s) and " . $stmt2->rowCount() . " affected users row(s)");
                    return $user;
                }
                Log::info("Exiting UserDataService.authenticate() with " . $stmt->rowCount() . " affected credentials row(s)");
                return null;
            }
            Log::info("Exiting UserDataService.authenticate() with no rows affected");
            return null;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Takes in a credentials model
     * Sets variable for username
     * Creates a SELECT sql statement from the database
     * Binds the parameter of the sql statement equal to the username
     * Executes the sql statement
     * Returns the number of rows found
     *
     * @param
     *            searchCredentials credentials to check
     * @return {@link Integer} number of rows found
     */
    function checkUsername($searchCredentials)
    {
        Log::info("Entering UserDataService.checkUsername()");
        try {
            $username = $searchCredentials->getUsername();

            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE USERNAME = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            Log::info("Exiting UserDataService.checkUsername() with " . $stmt->rowCount());
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Takes in a user model
     * Sets variables for first name and last name
     * Creates a SELECT sql statement from the database
     * Binds the parameters of the sql statement equal to the first name and last name
     * Executes the sql statement
     * If no rows found, returns empty array
     * If rows were found, sets variables for all column data
     * Creates a user from the variables
     * Adds the object to array
     * Repeats for each row
     * Returns the array
     *
     * @param
     *            searchUser user to search for
     * @return {@link Array} array of Users found
     */
    function readByName($searchUser)
    {
        Log::info("Entering UserDataService.findByName()");
        try {
            $firstname = $searchUser->getFirst_name();
            $lastname = $searchUser->getLast_name();

            $user_array = array();
            $stmt = $this->db->prepare('SELECT * FROM users
                                        WHERE FIRSTNAME = :firstname OR LASTNAME = :lastname');
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $result["ID"];
                    $first_name = $result['FIRSTNAME'];
                    $last_name = $result['LASTNAME'];
                    $location = $result['LOCATION'];
                    $summary = $result['SUMMARY'];
                    $role = $result['ROLE'];
                    $credentials_id = $result['CREDENTIALS_ID'];
                    $user = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);
                    array_push($user_array, $user);
                }
            }
            Log::info("Exiting UserDataService.findByName() with user array (" . $stmt->rowCount() . " row(s))");
            return $user_array;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Takes in a user model
     * Sets variable for credentials_id
     * Creates a SELECT sql statement from the database
     * Binds the parameter of the sql statement equal to credentials_id
     * Executes the sql statement
     * If no rows found, return null
     * If rows were found, set $suspended equal to SUSPENDED column data
     * If $suspended was not equal to zero, sets it equal to zero
     * Else sets it equal to one
     * Creates an UPDATE sql statement from the database
     * Binds id parameter equal to credentials_id and suspended paramter equal to suspended
     * Executes the sql statement
     * Returns the amount of rows affected
     *
     * @param
     *            toggleSuspendUser user to toggle
     * @return {@link Integer} number of rows affected
     */
    function toggleSuspend($toggleSuspendUser)
    {
        Log::info("Entering UserDataService.toggleSuspend()");
        try {
            $credentials_id = $toggleSuspendUser->getCredentials()->getId();

            $stmt = $this->db->prepare('SELECT SUSPENDED
                                        FROM credentials
                                        WHERE ID = :credentials_id
                                        LIMIT 1');
            $stmt->bindParam(':credentials_id', $credentials_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $suspended = $result["SUSPENDED"];
                }

                if ($suspended != 0) {
                    $suspended = 0;
                } else {
                    $suspended = 1;
                }

                $stmt2 = $this->db->prepare('UPDATE credentials
                                        SET SUSPENDED = :suspended
                                        WHERE ID = :credentials_id');
                $stmt2->bindParam(':suspended', $suspended);
                $stmt2->bindParam(':credentials_id', $credentials_id);

                $stmt2->execute();

                Log::info("Exit SecurityDAO.toggleSuspend() with " . $stmt2->rowCount());
                return $stmt2->rowCount();
            }
            Log::info("Exit SecurityDAO.toggleSuspend() with null");
            return null;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Takes in a user model
     * Sets variable for credentials_id
     * Creates a SELECT sql statement from the database
     * Binds the parameter of the sql statement equal to credentials_id
     * Executes the sql statement
     * If no rows found, return null
     * If rows were found, binds the paramters of the sql statement equal to the variables
     * Creates a credentials model with the variables
     * Sets the initial user model's credentials equal to the new credentials model
     * Returns the user model
     *
     * @param
     *            tUser to search for
     * @return {@link UserModel} user that was updated
     */
    private function setCredentials($searchUser)
    {
        Log::info("Entering UserDataService.readCredentials()");
        try {
            $credentials_id = $searchUser->getCredentials_id();

            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE ID = :credentials_id
                                        LIMIT 1');
            $stmt->bindParam(':credentials_id', $credentials_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $username = $result['USERNAME'];
                    $password = $result['PASSWORD'];
                    $suspended = $result['SUSPENDED'];
                }

                $credentials = new CredentialsModel($credentials_id, $username, $password);
                $credentials->setSuspended($suspended);
                $searchUser->setCredentials($credentials);

                Log::info("Exiting UserDataService.readCredentials() with success");
                return $searchUser;
            }
            Log::info("Exiting UserDataService.readCredentials() with failure");
            return null;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }
}