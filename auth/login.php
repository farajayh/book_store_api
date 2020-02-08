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
    include_once '../objects/users.php';

    require_once "../auth/Bcrypt.php";
    $bc = new Bcrypt();
    
    $database = new Database();
    $db = $database->db_conn();

    $user = new user($db);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
 
    // make sure data is not empty
    if(
        !empty($data->email) &&
        !empty($data->password)
    ){
        $user->email = $data->email;
        $password = $data->password;
        $result = $user->get_user();
        $password_hash = $result['password'];
        if($bc->check_password($password, $password_hash)){
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "Login successful."));
        }else{
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Invalid login details."));
        }
    }
?>