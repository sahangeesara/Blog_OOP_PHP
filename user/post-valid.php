<?php 
// include_once('../config/app.php');
include_once(__DIR__ .'/../controllers/PostController.php');

if (!isset($_SESSION['authenticated']) || !isset($_SESSION['auth_user'])) {
    redirect('Please login first', 'login/login.php', 'warning');
}

// Generate AI-style description draft
if (isset($_POST['generate_description'])) {
    header('Content-Type: application/json');

    $title = isset($_POST['title']) ? validateInput($db->conn, $_POST['title']) : '';
    if (trim($title) === '') {
        echo json_encode(['ok' => false, 'message' => 'Please enter a post title first.']);
        exit;
    }

    $posts = new PostController;
    $description = $posts->generateDescriptionFromTitle($title);

    echo json_encode(['ok' => true, 'description' => $description]);
    exit;
}

// Delete post
if (isset($_POST['delete_post'])) {
    if (isset($_SESSION['auth_user']['user_role']) && $_SESSION['auth_user']['user_role'] === 'admin') {
        $id = isset($_POST['delete_post']) ? validateInput($db->conn, $_POST['delete_post']) : '';
        if ($id === '' || !is_numeric($id)) {
            redirect('Invalid post id', 'admin.php', 'danger');
        }

        $postDele = new PostController;
        $result = $postDele->deletePost($id);
        if ($result) {
            redirect('Post deleted successfully', 'admin.php', 'success');
        }

        redirect('Failed to delete post', 'admin.php', 'danger');
    }

    redirect("You don't have access to delete posts", 'index.php', 'danger');
}

// Add post
if (isset($_POST['save_posts'])) {
    $description = isset($_POST['description']) ? trim(validateInput($db->conn, $_POST['description'])) : '';
    $title = isset($_POST['title']) ? trim(validateInput($db->conn, $_POST['title'])) : '';
    $image = (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) ? $_FILES['image'] : '';

    if ($title === '') {
        redirect('Post title is required', 'create.php', 'warning');
    }

    if ($description === '') {
        redirect('Post description is required', 'create.php', 'warning');
    }

    if ($image === '') {
        redirect('Post image is required', 'create.php', 'warning');
    }

    if (strlen($title) > 45) {
        redirect('Post title must be 45 characters or less', 'create.php', 'warning');
    }

    if (strlen($description) > 1500) {
        redirect('Post description must be 1500 characters or less', 'create.php', 'warning');
    }

    $posts = new PostController;
    $result = $posts->savePost($title, $image, $description);

    if ($result) {
        if ($_SESSION['auth_user']['user_role'] === 'admin') {
            redirect('Post created successfully', 'admin.php', 'success');
        }
        redirect('Post created successfully', 'index.php', 'success');
    }

    redirect('Failed to create post', 'create.php', 'danger');
}

// Update post
if (isset($_POST['update_posts'])) {
    $id = isset($_POST['id']) ? validateInput($db->conn, $_POST['id']) : '';
    $title = isset($_POST['title']) ? validateInput($db->conn, $_POST['title']) : '';
    $image = (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) ? $_FILES['image'] : '';
    $description = isset($_POST['description']) ? validateInput($db->conn, $_POST['description']) : '';

    if ($id === '' || !is_numeric($id)) {
        redirect('Invalid post id', 'index.php', 'danger');
    }

    if (trim($title) === '') {
        redirect('Post title is required', 'update.php?id=' . $id, 'warning');
    }

    if (trim($description) === '') {
        redirect('Post description is required', 'update.php?id=' . $id, 'warning');
    }

    if (strlen($title) > 45) {
        redirect('Post title must be 45 characters or less', 'update.php?id=' . $id, 'warning');
    }

    if (strlen($description) > 1500) {
        redirect('Post description must be 1500 characters or less', 'update.php?id=' . $id, 'warning');
    }

    $posts = new PostController;
    $result = $posts->upatePost($title, $image, $id, $description);

    if ($result) {
        if ($_SESSION['auth_user']['user_role'] === 'admin') {
            redirect('Post updated successfully', 'admin.php', 'success');
        }
        redirect('Post updated successfully', 'index.php', 'success');
    }

    redirect('Failed to update post', 'update.php?id=' . $id, 'danger');
}

?>