<?php
    class User{

        // database connection and table name
        private $conn;
        private $table_name = "users";

        public $first_name;
        public $last_name;
        public $password;
        public $email;
        private $user_type;
        
        public function __construct($db){
            $this->conn = $db;
        }

        function get_user(){
            // select user
            $query = "SELECT * FROM ".$this->table_name." WHERE email=? LIMIT 0,1";
                        
            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->email);

            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            if ($num>0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row;
            }
        }

        function add_user(){
            // query to insert new user record
            $query = "INSERT INTO
            " . $this->table_name . "
            SET
                first_name=:first_name, last_name=:last_name, email=:email, password=:password";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->first_name=htmlspecialchars(strip_tags($this->first_name));
            $this->last_name=htmlspecialchars(strip_tags($this->last_name));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->password=htmlspecialchars(strip_tags($this->password));

            // bind values
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":email", $this->email);            
            $stmt->bindParam(":password", $this->password);

            // execute query
            if($stmt->execute()){
            return true;
            }

            return false;
        }
         
        function user_exist(){
            // select user
            $query = "SELECT * FROM ".$this->table_name." WHERE email=? LIMIT 0,1";
                        
            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->email);

            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            if ($num>0) {
                return true;
            }

            return false;
        }

    }
?>