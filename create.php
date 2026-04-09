<?php
include_once(__DIR__ . '/config/app.php');

if (!isset($_SESSION['authenticated'])) {
    redirect('Please login to create a post', 'login/login.php', 'warning');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create Post</title>
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
                    <h1 class="h5 mb-0">Create New Post</h1>
                    <a href="index.php" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form action="user/post-valid.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="image" class="form-label">Cover Image</label>
                            <input id="image" type="file" name="image" class="form-control" accept="image/*" required>
                            <div class="form-text">Use a clear image for better engagement.</div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Post Title</label>
                            <input id="title" type="text" name="title" class="form-control" maxlength="45" placeholder="Enter a clear, short title" required>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="description" class="form-label mb-0">Post Description</label>
                                <button type="button" id="generateDescriptionBtn" class="btn btn-sm btn-outline-primary">Generate AI Draft</button>
                            </div>
                            <textarea id="description" name="description" rows="8" class="form-control" maxlength="1500" placeholder="Write your post content..." required></textarea>
                            <div class="form-text" id="generateStatus">Use AI draft and then edit it in your own words.</div>
                        </div>

                        <div class="d-grid d-sm-flex gap-2">
                            <button type="submit" class="btn btn-primary" name="save_posts">Publish Post</button>
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
            status.textContent = 'AI draft generated. You can now personalize it.';
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
