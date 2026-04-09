<?php
// include functions
include('../controllers/RegsterController.php');
include('../controllers/LoginController.php');
include('../controllers/PostController.php');

// objects
$postcont = new PostController();
$auth = new LoginController;

// Login
if (isset($_POST['login_btn'])) {
    $email = isset($_POST['email']) ? validateInput($db->conn, $_POST['email']) : '';
    $password = isset($_POST['password']) ? validateInput($db->conn, $_POST['password']) : '';

    if ($email === '' || $password === '') {
        redirect('Please enter your email and password', 'login/login.php', 'warning');
    }

    $checkLogin = $auth->userLogin($email, $password);

    if ($checkLogin) {
        if ($_SESSION['auth_user']['user_role'] === 'admin') {
            redirect('Login successful', 'admin.php', 'success');
        }

        redirect('Login successful', 'index.php', 'success');
    }

    redirect('Invalid email or password', 'login/login.php', 'danger');
}

// Register
if (isset($_POST['regstar_btn'])) {
    $name = isset($_POST['name']) ? validateInput($db->conn, $_POST['name']) : '';
    $email = isset($_POST['email']) ? validateInput($db->conn, $_POST['email']) : '';
    $role = isset($_POST['role']) ? validateInput($db->conn, $_POST['role']) : 'user';
    $password = isset($_POST['password']) ? validateInput($db->conn, $_POST['password']) : '';
    $confirmPassword = isset($_POST['confirm_Password']) ? validateInput($db->conn, $_POST['confirm_Password']) : '';

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '') {
        redirect('Please fill all registration fields', 'login/regstar.php', 'warning');
    }

    if ($role !== 'admin' && $role !== 'user') {
        $role = 'user';
    }

    $regster = new RegsterController;
    $resultPassword = $regster->confirmPassword($password, $confirmPassword);

    if (!$resultPassword) {
        redirect('Password and confirm password do not match', 'login/regstar.php', 'warning');
    }

    $resultUser = $regster->isUserExists($email);
    if ($resultUser) {
        redirect('Email already exists', 'login/regstar.php', 'warning');
    }

    $regsterQuery = $regster->registeration($name, $email, $password, $role);
    if ($regsterQuery) {
        redirect('Registered successfully. Please login.', 'login/login.php', 'success');
    }

    redirect('Something went wrong. Please try again.', 'login/regstar.php', 'danger');
}

?>