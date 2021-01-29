<?php

class Database{

    private $connection;
    private static $_instance = null;
    private $dbhost = "localhost"; // Ip Address of database if external connection.
    private $dbuser = "root";  // Username for DB
    private $dbpass = "";  // Password for DB
    private $dbname = "wallet";   // DB Name

    public static function getInstance(){
        if (!isset(static::$_instance)) {
            static::$_instance = new static;
        }
        return static::$_instance;
    }

    public function __construct() {   
        try{
            $this->connection = new PDO('mysql:host='.$this->dbhost.'; port=3306; dbname='.$this->dbname, $this->dbuser, $this->dbpass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Failed to connect to DB: ". $e->getMessage());
        }
    }
  
    private function __clone(){}
  
    public function getConnection(){
        return $this->connection;
    }
}

?>

