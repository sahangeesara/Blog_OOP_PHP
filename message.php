<?php
if (isset($_SESSION['message'])) {
    $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
    $allowedTypes = ['success', 'danger', 'warning', 'info'];

    if (!in_array($type, $allowedTypes, true)) {
        $type = 'info';
    }

    echo '<div class="container mt-3">';
    echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8');
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    echo '</div>';

    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

?>