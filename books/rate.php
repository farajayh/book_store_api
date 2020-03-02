<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    require_once "../config/db_connection.php";
    require_once "../objects/books.php";
    require_once "../auth/verification.php";

    // get database connection
    $database = new Database();
    $db = $database->db_conn();
    
    // initialise book object
    $book = new Book($db);
    
    // initialise token verifier
    $verify = new Verify();
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    $jwt = isset($data->token) ? $data->token : "";
    $verify->jwt = $jwt;
    
    if(!$jwt || $verify->check_jwt() == false){
        echo json_encode(
            array(
            "message" => "Access denied.",
            "error" => $verify->err
        ));
    }else{
        $decoded = $verify->decoded->data;
        if(
            !empty($data->rating) &&
            !empty($data->rating_on) &&
            0 <= $data->rating && $data->rating <= 10
        ){
            // set book property values
            $book->rating = $data->rating;
            $book->rating_by = $decoded->email;
            $book->rating_on = $data->rating_on;

            // rate the book
            if ($book->rate()) {
            
                // set response code - 200 ok
                http_response_code(200);
            
                // display message
                echo json_encode(array("message" => "book has been rated."));
            }
            
            // if unable to rate the book, display message
            else {
            
                // set response code - 503 service unavailable
                http_response_code(503);
            
                // display message
                echo json_encode(array("message" => "Unable to rate book."));
            }
        }else{
            //set response code - 503 service unavailable
            http_response_code(503);

            // display message
            echo json_encode(array("message" => "Unable to rate book. One or more missing/invalid parameter"));
        }
    }
?>