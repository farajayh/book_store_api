<?php
    class Book{
 
        // database connection and table name
        private $conn;
        private $table_name = "books";
     
        // book properties
        public $id;
        public $name;
        public $description;
        public $author;
        public $rating;
        public $rating_on;
        public $rating_by;
     
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }

        function read(){
            // select all query
            $query = "SELECT * FROM ".$this->table_name;
                        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
            return $stmt;
        }

        function get_avg_rating($book_id){
            $sql = "SELECT AVG(rating) FROM ratings WHERE rating_on=".$book_id;
            $stmt = $this->conn->prepare( $sql );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['AVG(rating)'];
        }

        function create(){
            // query to insert record
            $query = "INSERT INTO
            " . $this->table_name . "
            SET
                name=:name, author=:author, description=:description";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->author=htmlspecialchars(strip_tags($this->author));
            $this->description=htmlspecialchars(strip_tags($this->description));

            // bind values
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":author", $this->author);
            $stmt->bindParam(":description", $this->description);

            // execute query
            if($stmt->execute()){
            return true;
            }

            return false;
        }

        function readOne(){
 
            // query to read single record
            $query = "SELECT * FROM ".$this->table_name." WHERE id=? LIMIT 0,1";
        
            // prepare query statement
            $stmt = $this->conn->prepare( $query );

            // bind id of book to be read
            $stmt->bindParam(1, $this->id);
         
            // execute query
            $stmt->execute();
         
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // set values to object properties
            $this->name = $row['name'];
            $this->author = $row['author'];
            $this->description = $row['description'];
        }

        function update(){
            // update query
            $query = "UPDATE
            " . $this->table_name . "
            SET
                name = :name,
                author = :author,
                description = :description
            WHERE
                id = :id";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->author=htmlspecialchars(strip_tags($this->author));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->id=htmlspecialchars(strip_tags($this->id));

            // bind new values
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':id', $this->id);

            // execute the query
            if($stmt->execute()){
            return true;
            }

            return false;
        }

        function rate(){
            // query to insert record
            $query = "INSERT INTO ratings
            SET
                rating=:rating, rating_on=:rating_on, rating_by=:rating_by";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->rating=htmlspecialchars(strip_tags($this->rating));
            $this->rating_on=htmlspecialchars(strip_tags($this->rating_on));
            $this->rating_by=htmlspecialchars(strip_tags($this->rating_by));

            // bind values
            $stmt->bindParam(":rating", $this->rating);
            $stmt->bindParam(":rating_on", $this->rating_on);
            $stmt->bindParam(":rating_by", $this->rating_by);

            // execute query
            if($stmt->execute()){
            return true;
            }

            return false;
        }
        
        function delete(){
            // delete query
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind id of record to delete
            $stmt->bindParam(1, $this->id);
        
            // execute query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }
    }
?>