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
    function create2($newCredentials, $newUser)
    {
        Log::info("Entering UserDataService.create()");
        try {
            $username = $newCredentials->getUsername();
            $password = $newCredentials->getPassword();

            $stmt = $this->db->prepare('INSERT INTO CREDENTIALS
                                        (USERNAME, PASSWORD)
                                        VALUES (:username, :password)');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            // insert into users
            if ($stmt->rowCount() == 1) {

                $first_name = $newUser->getFirst_name();
                $last_name = $newUser->getLast_name();
                $location = $newUser->getLocation();
                $summary = $newUser->getSummary();
                $role = 0;
                $credentials_id = $this->db->lastInsertId();

                $stmt2 = $this->db->prepare('INSERT INTO USERS
                                        (FIRSTNAME, LASTNAME, LOCATION, SUMMARY, ROLE, CREDENTIALS_ID)
                                        VALUES (:first_name, :last_name, :location, :summary, :role, :credentials_id)');
                $stmt2->bindParam(':first_name', $first_name);
                $stmt2->bindParam(':last_name', $last_name);
                $stmt2->bindParam(':location', $location);
                $stmt2->bindParam(':summary', $summary);
                $stmt2->bindParam(':role', $role);
                $stmt2->bindParam(':credentials_id', $credentials_id);
                $stmt2->execute();
            }

            Log::info("Exit SecurityDAO.create() with " . $stmt2->rowCount());
            return $stmt2->rowCount();
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
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
                Log::info("Exiting UserDataService.read() with " . $user . " (" . $stmt->rowCount() . " affected rows)");
                return $user;
            }
            return null;
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
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
            }

            Log::info("Exiting UserDataService.readAll() with user array (" . $stmt->rowCount() . " rows)");
            return $user_array;
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    private function setCredentials($searchUser)
    {
        Log::info("Entering UserDataService.readCredentials()");
        $credentials_id = $searchUser->getCredentials_id();
        try {
            $stmt = $this->db->prepare('SELECT * FROM credentials
                                                    WHERE ID = :credentials_id
                                                    LIMIT 1');
            $stmt->bindParam(':credentials_id', $credentials_id);
            $stmt->execute();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $username = $result['USERNAME'];
                $password = $result['PASSWORD'];
                $suspended = $result['SUSPENDED'];
            }

            $credentials = new CredentialsModel($credentials_id, $username, $password);
            $credentials->setSuspended($suspended);
            $searchUser->setCredentials($credentials);
            return $searchUser;
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
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

            $stmt = $this->db->prepare('UPDATE USERS
                                        SET FIRSTNAME = :first_name, LASTNAME = :last_name, LOCATION = :location, SUMMARY = :summary)
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
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
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

            $stmt = $this->db->prepare('DELETE FROM USERS
                                        WHERE ID = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                try {
                    $credentials_id = $deleteUser->getCredentials_id();

                    $stmt2 = $this->db->prepare('DELETE FROM CREDENTIALS
                                        WHERE ID = :credentials_id');
                    $stmt2->bindParam(':credentials_id', $credentials_id);
                    $stmt2->execute();
                } catch (PDOException $e) {
                    // catch all excpetions
                    // log exception, do not throw technology specific excpetions, throw a custome exception
                    Log::error("Exception ", array(
                        "message" => $e->getMessage()
                    ));
                    throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
                }
            }
            Log::info("Exit SecurityDAO.delete() with " . $stmt->rowCount());
            return $stmt->rowCount();
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Takes in name string
     * Connects to the database
     * Creates and executes a sql statement to find the user
     * Returns the user that was found
     *
     * @param
     *            n first name of user to find
     * @return {@link User} user that was found
     */
    function findByName($searchUser)
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

            Log::info("Exiting UserDataService.findByName() with " . $user_array . " (" . $stmt->rowCount() . " rows)");
            return $user_array;
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Takes in username and password strings
     * Connects to the database
     * Creates and executes a sql statement to validate the user
     * Returns the user that was logged in
     *
     * @param
     *            us, pa credentials to log in with
     * @return {@link User} user that was logged in
     */
    function authenticate($loginCredentials)
    {
        Log::info("Entering UserDataService.authenticate()");
        try {
            $username = $loginCredentials->getUsername();
            $password = $loginCredentials->getPassword();

            // check credentials
            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE USERNAME = :username AND PASSWORD = :password');

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $credentials_id = $result['ID'];
                $suspended = $result['SUSPENDED'];
            }
            // return user
            if ($stmt->rowCount() == 1) {
            if($suspended != 0){
                $msg = -1;
                return $msg;
            }

                $stmt2 = $this->db->prepare('SELECT * FROM users
                                        WHERE CREDENTIALS_ID = :credentials_id');
                $stmt2->bindParam(':credentials_id', $credentials_id);
                $stmt2->execute();

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
            Log::info("Exiting UserDataService.authenticate() with " . $user . " (" . $stmt2->rowCount() . " affected rows)");
            return $user;
            }
            return null;

        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    function checkUsername($searchCredentials)
    {
        Log::info("Entering UserDataService.checkUsername()");
        try {
            $username = $searchCredentials->getUsername();

            // check credentials
            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE USERNAME = :username');

            $stmt->bindParam(':username', $username);
            $stmt->execute();

            Log::info("Exiting UserDataService.checkUsername() with " . $stmt->rowCount());
            return $stmt->rowCount();
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

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

            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $suspended = $result["SUSPENDED"];
            }
            if ($suspended != 0) {
                $suspended = 0;
            }
            else {
                $suspended = 1;
            }
            
            if ($stmt->rowCount() > 0) {
                try {
                    $stmt2 = $this->db->prepare('UPDATE credentials
                                        SET SUSPENDED = :suspended
                                        WHERE ID = :credentials_id');
                    $stmt2->bindParam(':suspended', $suspended);
                    $stmt2->bindParam(':credentials_id', $credentials_id);
                    
                    $stmt2->execute();          
                    
                    Log::info("Exit SecurityDAO.toggleSuspend() with " . $stmt2->rowCount());
                    return $stmt2->rowCount();
                } catch (PDOException $e) {
                    // catch all excpetions
                    // log exception, do not throw technology specific excpetions, throw a custome exception
                    Log::error("Exception ", array(
                        "message" => $e->getMessage()
                    ));
                    throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
                }
            }
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    // not implemented
    function create($newUser)
    {}
}