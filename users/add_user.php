<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // include database and object files
    require_once "../config/db_connection.php";
    require_once "../objects/users.php";
    require_once "../auth/Bcrypt.php";

    $database = new Database();
    $db = $database->db_conn();

    $user = new user($db);
    
    $bc = new bcrypt();

    $data = json_decode(file_get_contents("php://input"));

    if(
        !empty($data->first_name) &&
        !empty($data->last_name) &&
        !empty($data->email) &&
        !empty($data->password)
      ){
        $user->first_name = $data->first_name;
        $user->last_name = $data->last_name;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->password = $bc->hash_password($user->password);

        if($user->user_exist() == false){
            if($user->add_user()){
                // set response code - 201 created
                http_response_code(201);
        
                // display message
                echo json_encode(array("message" => "User was added successfully."));
            }else{
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // display message
                echo json_encode(array("message" => "Unable to add new user."));
            }
        }else{
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // display message
                echo json_encode(array("message" => "A user with that email already exists"));
        }
        
    }
?>