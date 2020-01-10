<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/db_connection.php';
    include_once '../objects/books.php';
    
    // get database connection
    $database = new Database();
    $db = $database->db_conn();
    
    // prepare book object
    $book = new Book($db);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    if(
        !empty($data->rating) &&
        !empty($data->rating_by) &&
        !empty($data->rating_on) &&
        0 <= $data->rating && $data->rating <= 10
    ){
        // set book property values
        $book->rating = $data->rating;
        $book->rating_by = $data->rating_by;
        $book->rating_on = $data->rating_on;

        // rate the book
        if ($book->rate()) {
        
            // set response code - 200 ok
            http_response_code(200);
        
            // tell the user
            echo json_encode(array("message" => "book has been rated."));
        }
        
        // if unable to rate the book, tell the user
        else {
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to rate book."));
        }
    }
?>