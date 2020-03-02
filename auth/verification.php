<?php
    require_once "../vendor/autoload.php";
    use Firebase\JWT\JWT;
    
    class Verify{
        public $err = "No token provided";
        public $jwt;
        public $decoded;
        private $secret_key = "$2a$08l9bw8Noni4Bd/ka9RJYK0u3cSOCIC81YgJs";

        function check_jwt(){
            try{
                $this->decoded = JWT::decode($this->jwt, $this->secret_key, array('HS256'));
                return true;
            }catch(Exception $e){
                $this->err = $e->getMessage();
                return false;
            }
        }
    }
?>