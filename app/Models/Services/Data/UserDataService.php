<?php
namespace App\Models\Services\Data;

use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;
use App\Models\Utility\DatabaseException;
use App\Models\UserModel;

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
    function create($newCredentials, $newUser)
    {
        Log::info("Entering UserDataService.create()");
        try {
            $stmt = $this->db->prepare('INSERT INTO CREDENTIALS
                                        (USERNAME, PASSWORD)
                                        WHERE USERNAME = :username AND PASSWORD = :password');
            $stmt->bindParam(':username', $newCredentials->getUsername());
            $stmt->bindParam(':password', $newCredentials->getPassword());
            $stmt->execute();

            // insert into users
            if ($stmt->affected_rows == 1) {

                $first_name = $newUser->getFirst_name();
                $last_name = $newUser->getLast_name();
                $location = $newUser->getLocation();
                $summary = $newUser->getSummary();
                $credentials_id = $this->db->lastInsertId();

                $stmt2 = $this->db->prepare('INSERT INTO USERS
                                        (FIRSTNAME, LASTNAME, LOCATION, SUMMARY, CREDENTIALS_ID)
                                        WHERE FIRSTNAME = :first_name AND LASTNAME = :last_name AND LOCATION = :location AND SUMMARY = :summary AND CREDENTIALS_ID = :credentials_id');
                $stmt2->bindParam(':first_name', $first_name);
                $stmt2->bindParam(':last_name', $last_name);
                $stmt2->bindParam(':location', $location);
                $stmt2->bindParam(':summary', $summary);
                $stmt2->bindParam(':credentials_id', $credentials_id);
                $stmt2->execute();
            }

            Log::info("Exit SecurityDAO.create() with " . $stmt2->affected_rows);
            return $stmt2->affected_rows;
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

            $stmt = $this->db->prepare('SELECT * FROM users
                                        WHERE ID = :id');
            $stmt->bindParam(':id', $searchUser->getId());
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
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
            }

            Log::info("Exiting UserDataService.read() with " . $user . " (" . $stmt->affected_rows . " affected rows)");
            return $user;
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
                    array_push($user_array, $user);
                }
            }

            Log::info("Exiting UserDataService.readAll() with " . $user_array . " (" . $stmt->rowCount() . " rows)");
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
     *
     * @see DataServiceInterface update
     */
    function update($updatedUser)
    {
        Log::info("Entering UserDataService.update()");
        try {
            $stmt = $this->db->prepare('UPDATE USERS
                                        (FIRSTNAME, LASTNAME, LOCATION, SUMMARY)
                                        WHERE FIRSTNAME = :first_name AND LASTNAME = :last_name AND LOCATION = :location AND SUMMARY = :summary');

            $stmt->bindParam(':first_name', $updatedUser->getFirst_name());
            $stmt->bindParam(':last_name', $updatedUser->getLast_name());
            $stmt->bindParam(':location', $updatedUser->getLocation());
            $stmt->bindParam(':summary', $updatedUser->getSummary());
            $stmt->execute();

            Log::info("Exit SecurityDAO.update() with " . $stmt->affected_rows);
            return $stmt->affected_rows;
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
            $stmt = $this->db->prepare('DELETE FROM USERS
                                        WHERE ID = :id');
            $stmt->bindParam(':id', $deleteUser->getId());
            $stmt->execute();

            Log::info("Exit SecurityDAO.delete() with " . $stmt->affected_rows);
            return $stmt->affected_rows;
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
            $user_array = array();
            $stmt = $this->db->prepare('SELECT * FROM users
                                        WHERE FIRSTNAME = :firstname OR LASTNAME = :lastname');

            $stmt->bindParam(':firstname', $searchUser->getFirst_name());
            $stmt->bindParam(':lastname', $searchUser->getLast_name());
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
            // check credentials
            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE USERNAME = :username AND PASSWORD = :password');

            $stmt->bindParam(':username', $loginCredentials->getUsername());
            $stmt->bindParam(':password', $loginCredentials->getPassword());
            $stmt->execute();

            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $credentials_id = $result['CREDENTIALS_ID'];
            }

            // return user
            if ($stmt->rowCount() == 1) {

                $stmt2 = $this->db->prepare('SELECT * FROM users
                                        WHERE CREDENTIALS_ID = :credentials_id');
                $stmt2->bindParam(':credentials_id', $credentials_id);
                $stmt2->execute();

                while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    $id = $result2["ID"];
                }
            }

            Log::info("Exiting UserDataService.authenticate() with " . $id . " (" . $stmt2->affected_rows . " affected rows)");
            return $id;
        } catch (PDOException $e) {
            // catch all excpetions
            // log exception, do not throw technology specific excpetions, throw a custome exception
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }
}