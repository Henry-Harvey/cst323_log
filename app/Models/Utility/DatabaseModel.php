<?php
namespace App\Models\Utility;

use \PDO;

class DatabaseModel{
     
    /**
     * Creates a new database
     * returns the database
     *
     * @return {@link mysqli}		database that was created
     */
    function getDb(){
        $servername = config("database.connections.mysql.host");
        $port = config("database.connections.mysql.port");
        $dbname = config("database.connections.mysql.database");
        $username = config("database.connections.mysql.username");
        $password = config("database.connections.mysql.password");
        
        $db = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db;           
    }
        
}