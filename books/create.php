<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
    // get database connection
    include_once '../config/db_connection.php';
    
    // instantiate book object
    include_once '../objects/books.php';
    
    $database = new Database();
    $db = $database->db_conn();
    
    $book = new book($db);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
 
    // make sure data is not empty
    if(
        !empty($data->name) &&
        !empty($data->author) &&
        !empty($data->description)
    ){
    
        // set book property values
        $book->name = $data->name;
        $book->author = $data->author;
        $book->description = $data->description;
    
        // create the book
        if($book->create()){
    
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "Book was created."));
        }
    
        // if unable to create the book, tell the user
        else{
    
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Unable to create book."));
        }
    }
?>