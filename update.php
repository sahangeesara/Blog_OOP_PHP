<?php
include_once(__DIR__ . '/controllers/PostController.php');

if (!isset($_SESSION['authenticated'])) {
    redirect('Please login first', 'login/login.php', 'warning');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('Invalid post selected', 'index.php', 'danger');
}

$post = new PostController;
$result = $post->edit((int)$_GET['id']);

if (!$result) {
    redirect('Post not found', 'index.php', 'danger');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Update Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-light">
<?php include('components/nav.php'); ?>
<?php include('message.php'); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h1 class="h5 mb-0">Update Post</h1>
                    <a href="index.php" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form action="user/post-valid.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo (int)$result['id']; ?>">

                        <?php if (!empty($result['image'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="<?php echo htmlspecialchars($result['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Current post image" class="img-fluid rounded" style="max-height: 220px;">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="image" class="form-label">Replace Image (optional)</label>
                            <input id="image" type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Post Title</label>
                            <input id="title" type="text" name="title" class="form-control" maxlength="45" value="<?php echo htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="description" class="form-label mb-0">Post Description</label>
                                <button type="button" id="generateDescriptionBtn" class="btn btn-sm btn-outline-primary">Generate AI Draft</button>
                            </div>
                            <textarea id="description" name="description" rows="8" class="form-control" maxlength="1500" required><?php echo htmlspecialchars($result['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <div class="form-text" id="generateStatus">Generate a fresh draft and edit it to match your style.</div>
                        </div>

                        <div class="d-grid d-sm-flex gap-2">
                            <button type="submit" class="btn btn-primary" name="update_posts">Update Post</button>
                            <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
document.getElementById('generateDescriptionBtn').addEventListener('click', function () {
    var titleInput = document.getElementById('title');
    var descInput = document.getElementById('description');
    var status = document.getElementById('generateStatus');

    var title = titleInput.value.trim();
    if (title === '') {
        status.textContent = 'Please enter a post title first.';
        status.className = 'form-text text-danger';
        return;
    }

    if (descInput.value.trim() !== '' && !confirm('Replace the current description with a new AI draft?')) {
        return;
    }

    status.textContent = 'Generating description...';
    status.className = 'form-text text-primary';

    var formData = new FormData();
    formData.append('generate_description', '1');
    formData.append('title', title);

    fetch('user/post-valid.php', {
        method: 'POST',
        body: formData
    })
    .then(function (response) { return response.json(); })
    .then(function (data) {
        if (data.ok) {
            descInput.value = data.description;
            status.textContent = 'AI draft generated. Please review before saving.';
            status.className = 'form-text text-success';
            return;
        }

        status.textContent = data.message || 'Could not generate draft.';
        status.className = 'form-text text-danger';
    })
    .catch(function () {
        status.textContent = 'Network error while generating description.';
        status.className = 'form-text text-danger';
    });
});
</script>
</body>
</html>
