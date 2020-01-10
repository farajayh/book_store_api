<?php
    class Database{
 
        //database parameters
        private $host = "localhost";
        private $db_name = "book_store";
        private $username = "root";
        private $password = "";
        public $conn;
     
        // database connection
        public function db_conn(){
     
            $this->conn = null;
     
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            }catch(PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }
     
            return $this->conn;
        }
    }
?>