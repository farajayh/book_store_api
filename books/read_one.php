<?php
        // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    // include database and object files
    include_once '../config/db_connection.php';
    include_once '../objects/books.php';
    // get database connection
    $database = new Database();
    $db = $database->db_conn();
    
    // prepare book object
    $book = new Book($db);
    
    // set ID property of record to read
    $book->id = isset($_GET['id']) ? $_GET['id'] : die();
    
    // read the details of the book
    $book->readOne();
    
    if($book->name!=null){
        // create array
        $book_arr = array(
            "id" =>  $book->id,
            "name" => $book->name,
            "description" => $book->description,
            "author" => $book->author,
            "avg_rating" => round($book->get_avg_rating($book->id), 1)
        );
    
        // set response code - 200 OK
        http_response_code(200);
    
        // make it json format
        echo json_encode($book_arr);
    }
    
    else{
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user book does not exist
        echo json_encode(array("message" => "book does not exist."));
    }
?>