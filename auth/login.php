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
        $user->password = $data->password;
    }
?>