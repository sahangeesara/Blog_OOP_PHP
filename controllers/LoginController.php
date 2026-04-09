<?php
include_once('Controller.php');

class LoginController extends Controller
{
   
    //userLogin
    public function userLogin($email, $password)
    {
        $checkLogin ="SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $result = $this->conn->query($checkLogin);
        if ($result->num_rows > 0)
        {
            $data = $result -> fetch_assoc();
            $this-> userAuthentication($data);
            return true;

        }else
        {
            return false;
        }
    }

    //userAuthentication
    public function userAuthentication($data)
    {
        $_SESSION['authenticated'] = true;
       

        $_SESSION['auth_user'] =
        [
            'user_id' => $data['id'],
            'user_name' => $data['name'],
            'user_email' => $data['email'],
            'user_role' => $data['role'],
        ];

    }

}


?>
