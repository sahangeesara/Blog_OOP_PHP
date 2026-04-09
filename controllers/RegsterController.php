<?php

// namespace conntrollers;

// include('../config/app.php');
include_once('Controller.php');


class RegsterController extends Controller
{
        //registeration user function
        public function registeration($name,$email,$password,$role)
        {
            $regster_qury ="INSERT INTO users (name,email,password,role,create_at) VALUES('$name','$email','$password','$role', NOW())";
            $result = $this->conn->query($regster_qury);
            return $result;
       
       
        }

        //isUserExists function
        public function isUserExists($email)
        {
            $checkUser ="SELECT email FROM users WHERE email='$email' limit 1";
            $result = $this->conn->query($checkUser);
            if ($result->num_rows > 0) {
                return  true;
            }else {
                return false;
            }
       
       
        }


        //confirmPassword function
        public function confirmPassword($password,$c_password)
        {
         if($password == $c_password){
            return true;
         }else{
            return false;
         }

        }

}

?>