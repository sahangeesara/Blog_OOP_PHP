<?php
include_once('controllers/PostController.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('Invalid post selected', 'index.php', 'warning');
}

$postController = new PostController;
$post = $postController->edit((int)$_GET['id']);

if (!$post) {
    redirect('Post not found', 'index.php', 'danger');
}

$createdAtRaw = isset($post['create_date']) ? trim((string)$post['create_date']) : '';
$createdAtTs = $createdAtRaw !== '' ? strtotime($createdAtRaw) : false;
$createdAtLabel = $createdAtTs ? date('M d, Y', $createdAtTs) : 'Date not available';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?> - SL Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/app.css">
</head>
<body class="bg-light blog-page">
<?php include('components/nav.php'); ?>
<?php include('message.php'); ?>

<div class="container py-4">
    <div class="mb-3">
        <a href="index.php" class="btn btn-sm btn-outline-secondary">Back to Posts</a>
    </div>

    <article class="card shadow-sm">
        <img src="<?php echo htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" alt="Post image" style="max-height: 420px; object-fit: cover;">
        <div class="card-body">
            <h1 class="h3 mb-2"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="text-muted mb-3">Created: <?php echo htmlspecialchars($createdAtLabel, ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="post-content"><?php echo nl2br(htmlspecialchars((string)$post['description'], ENT_QUOTES, 'UTF-8')); ?></div>
        </div>
    </article>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

