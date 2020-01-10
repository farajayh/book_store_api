<?php
    class User{

        // database connection and table name
        private $conn;
        private $table_name = "users";

        public $email;
        public $password_hash;
        
        public function __construct($db){
            $this->conn = $db;
        }

        function get_user(){
            // select user
            $query = "SELECT * FROM ".$this->table_name." WHERE username=".$this->username.
                        " and password=".$this->password_hash."LIMIT 0,1";
                        
            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // execute query
            $stmt->execute();
            
            $num = $stmt->rowCount();
            if($num>0){
                return true;
            }else{
                return false;
            }
            
        }

    }
?>