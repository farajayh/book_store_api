<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object file
    include_once '../config/db_connection.php';
    include_once '../objects/books.php';
    
    // get database connection
    $database = new Database();
    $db = $database->db_conn();
    
    // prepare book object
    $book = new Book($db);
    
    // get book id
    $data = json_decode(file_get_contents("php://input"));
    
    // set book id to be deleted
    $book->id = $data->id;
    
    // delete the book
    if($book->delete()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "book was deleted."));
    }
    
    // if unable to delete the book
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to delete book."));
    }
?>