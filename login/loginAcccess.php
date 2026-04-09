<?php
if (!isset($_SESSION['authenticated']) || !isset($_SESSION['auth_user'])) {
    redirect('Please login first', 'login/login.php', 'warning');
}

if (!isset($_SESSION['auth_user']['user_role']) || $_SESSION['auth_user']['user_role'] !== 'admin') {
    redirect("You don't have access to admin panel", 'index.php', 'danger');
}
?>