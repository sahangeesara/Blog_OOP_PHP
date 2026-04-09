<?php
include_once('controllers/PostController.php');
include_once('login/loginAcccess.php');

$posts = new PostController;
$result = $posts->index();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-light">
<?php include('components/nav.php'); ?>
<?php include('message.php'); ?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <h1 class="h3 mb-0">Admin Panel</h1>
        <div class="d-flex gap-2">
            <a href="create.php" class="btn btn-primary btn-sm">Create Post</a>
            <a href="index.php" class="btn btn-outline-secondary btn-sm">View Site</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 60px;">#</th>
                            <th scope="col" style="width: 100px;">Image</th>
                            <th scope="col">Title</th>
                            <th scope="col" style="width: 220px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result): ?>
                        <?php foreach ($result as $i => $post): ?>
                            <tr>
                                <th scope="row"><?php echo $i + 1; ?></th>
                                <td>
                                    <img src="<?php echo htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Post image" class="rounded" style="width: 64px; height: 64px; object-fit: cover;">
                                </td>
                                <td><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a href="update.php?id=<?php echo (int)$post['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form style="display:inline-block" method="post" action="user/post-valid.php" onsubmit="return confirm('Delete this post?');">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" name="delete_post" value="<?php echo (int)$post['id']; ?>">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No records found</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>