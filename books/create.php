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
    
    // database connection
    $database = new Database();
    $db = $database->db_conn();
    
    // initialise book object
    $book = new book($db);

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

        // make sure data is not empty
        if (
        !empty($data->name) &&
        !empty($data->author) &&
        !empty($data->description)
        ) {
            if($decoded->user_type != 'admin'){
                echo json_encode(
                    array(
                    "message" => "Access denied. Only an admin can create a book"
                ));
            }else{

                // set book property values
                $book->name = $data->name;
                $book->author = $data->author;
                $book->description = $data->description;
        
                // create the book
                if ($book->create()) {
        
                // set response code - 201 created
                    http_response_code(201);
        
                    // display message
                    echo json_encode(array("message" => "Book was created."));
                }
        
                // if unable to create the book, display message
                else {
        
                    // set response code - 503 service unavailable
                    http_response_code(503);
        
                    // display message
                    echo json_encode(array("message" => "Unable to create book."));
                }
            }
        }else{
            //set response code - 503 service unavailable
            http_response_code(503);

            // display message
            echo json_encode(array("message" => "Unable to create book. One or more missing/invalid parameter"));
        }
    }
?>