<?php
namespace App\Models\Services\Data;

use App\Models\Services\Data\Database;
use App\Models\User;

class UserDataService implements DataServiceInterface
{
    
    function create($user)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("
        INSERT INTO users
        (FIRSTNAME, LASTNAME, USERNAME, PASSWORD, ROLE)
        VALUES (?,?,?,?,?)");

        if (! $stmt) {
            echo "Something wrong in the binding process. sql error?";
            exit();
        }
        
        $fn = $user->getFirst_name();
        $ln = $user->getLast_name();
        $u = $user->getUsername();
        $p = $user->getPassword();
        $r = $user->getRole();
        
        $stmt->bind_param("ssssi", $fn, $ln, $u, $p, $r);
              
        $stmt->execute();

        $connection->close();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function read($id)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("
        SELECT *
        FROM users
        WHERE ID LIKE ?
        LIMIT 1");
        
        if (! $stmt) {
            echo "Something wrong in the binding process. sql error?";
            exit();
        }
        
        // $like_id = "%" . $id . "%";
        $stmt->bind_param("i", $id);
        
        $stmt->execute();
        
        $stmt->store_result();
        
        $stmt->bind_result($user_id, $first_name, $last_name, $username, $password, $role);
        
        while ($user = $stmt->fetch()) {
            $u = new User($user_id, $first_name, $last_name, $username, $password, $role);
        }
        
        $connection->close();
        return $u;
    }
    
    function readAll()
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("
        SELECT *
        FROM users");
        
        if (! $stmt) {
            echo "Something wrong in the binding process. sql error?";
            exit();
        }
        
        $stmt->execute();
        
        $stmt->store_result();
        
        $stmt->bind_result($user_id, $first_name, $last_name, $username, $password, $role);
        
        $user_array = array();
        while ($user = $stmt->fetch()) {
            $u = new User($user_id, $first_name, $last_name, $username, $password, $role);
            array_push($user_array, $u);
        }
        
        $connection->close();
        return $user_array;
    }
    
    function update($user)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("
        UPDATE users
        SET FIRSTNAME = ?, LASTNAME = ?, USERNAME = ?, PASSWORD = ?, ROLE = ?
        WHERE ID = ?
        LIMIT 1");
        
        if (! $stmt) {
            echo "Something wrong in the binding process. sql error?";
            exit();
        }
        
        $fn = $user->getFirst_name();
        $ln = $user->getLast_name();
        $u = $user->getUsername();
        $p = $user->getPassword();
        $r = $user->getRole();
        $id = $user->getId();
        
        $stmt->bind_param("ssssii", $fn, $ln, $u, $p, $r, $id);
        
        $stmt->execute();
        
        $connection->close();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function delete($id)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("
        DELETE FROM users
        WHERE ID = ?
        LIMIT 1");
        
        if (! $stmt) {
            echo "Something wrong in the binding process. sql error?";
            exit();
        }
        
        $stmt->bind_param("i", $id);
        
        $stmt->execute();
        
        $connection->close();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function findByFirstName($n)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT users.ID, FIRSTNAME, LASTNAME, USERNAME, PASSWORD, ROLE
        FROM users
        WHERE FIRSTNAME LIKE ?");
        
        if (! $stmt) {
            echo "Something wrong in the binding process. sql error?";
            exit();
        }
        
        $like_n = "%" . $n . "%";
        $stmt->bind_param("s", $like_n);
        
        $stmt->execute();
        
        $stmt->store_result();
        
        $stmt->bind_result($user_id, $first_name, $last_name, $username, $password, $role);
        
        $user_array = array();
        while ($user = $stmt->fetch()) {
            $u = new User($user_id, $first_name, $last_name, $username, $password, $role);
            array_push($user_array, $u);
        }
        
        $connection->close();
        return $user_array;
    }
    
    function login($us, $pa)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("
        SELECT *
        FROM users
        WHERE USERNAME = ? AND PASSWORD = ?
        LIMIT 1");
        
        if (! $stmt) {
            echo "Something wrong in the binding process. sql error?";
            exit();
        }
        
        // $like_id = "%" . $id . "%";
        $stmt->bind_param("ss", $us, $pa);
        
        $stmt->execute();
        
        $stmt->store_result();
        
        $stmt->bind_result($user_id, $first_name, $last_name, $username, $password, $role);
        
        while ($user = $stmt->fetch()) {
            $u = new User($user_id, $first_name, $last_name, $username, $password, $role);
        }
        
        $connection->close();
        if ($stmt->num_rows == 1) {
            $_SESSION['name'] = $u->getFirst_name();
            $_SESSION['userid'] = $u->getId();
            $_SESSION['role'] = $u->getRole();
            return true;
        } else {
            return false;
        }
    }
}