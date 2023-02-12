<?php

namespace core;

use PDO;
use PDOException;

class Database
{
    /**
     * @return PDO|void
     */
    static public function getConnection()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        try{
            $conn = new PDO("mysql:host=$servername;dbname=panda_team", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}