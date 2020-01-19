<?php
namespace App\Models\Services\Data;

use \mysqli;

class Database{
    
    private $dbservername = "localhost";
    private $dbusername = "root";
    private $dbpassword = "root";
    private $dbname = "networkingdb";
    
    function getConnection(){
        $conn = new mysqli($this->dbservername, $this->dbusername, $this->dbpassword, $this->dbname);

        if($conn->connect_error){
            echo "Connection failed " . $conn->connect_error . "<br>";
        }
        else{
            return $conn;
        }
              
    }
        
}