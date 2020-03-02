<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
    // get database connection
    include_once "../config/db_connection.php";
    
    //include JWT library
    require_once "../vendor/autoload.php";
    use Firebase\JWT\JWT;

    // instantiate book object
    include_once "../objects/users.php";

    require_once "../auth/Bcrypt.php";
    $bc = new Bcrypt();
    
    $database = new Database();
    $db = $database->db_conn();

    $user = new user($db);
    
    // get data
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
            
            // generate token
            $secret_key = "$2a$08l9bw8Noni4Bd/ka9RJYK0u3cSOCIC81YgJs";
            $issuer_claim = "localhost_server";
            $audience_claim = "book_client";
            $issued_at_claim = time();
            $not_before_claim = time()+10;
            $expire_claim = time()+180;

            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issued_at_claim,
                "nbf" => $not_before_claim,
                "exp" => $expire_claim,
                "data" => array (
                "id" => $result['id'],
                "first_name" => $result['first_name'],
                "last_name" => $result['last_name'],
                "email" => $result['email'],
                "user_type" => $result['user_type']
            ));

            $jwt = JWT::encode($token, $secret_key);
            
            // show output to the user
            echo json_encode(
                array(
                    "message" => "Login successful.",
                    "token" => $jwt,
                    "expires_at" => date(" d/m/y G:i:s", $expire_claim)
            ));
        }else{
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // display message
            echo json_encode(array("message" => "Invalid login details."));
        }
    }
?>