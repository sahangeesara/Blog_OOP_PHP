<?php
include_once('controllers/PostController.php');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

$posts = new PostController;
$result = $posts->index();
$postList = [];

if ($result) {
    foreach ($result as $post) {
        $postList[] = $post;
    }
}

if ($search !== '') {
    $postList = array_filter($postList, function ($post) use ($search) {
        return stripos($post['title'], $search) !== false || stripos($post['description'], $search) !== false;
    });
}

usort($postList, function ($a, $b) use ($sort) {
    $aDate = isset($a['create_date']) ? trim((string)$a['create_date']) : '';
    $bDate = isset($b['create_date']) ? trim((string)$b['create_date']) : '';

    $aTime = $aDate !== '' ? strtotime($aDate) : 0;
    $bTime = $bDate !== '' ? strtotime($bDate) : 0;

    $aTime = $aTime === false ? 0 : $aTime;
    $bTime = $bTime === false ? 0 : $bTime;

    if ($sort === 'oldest') {
        return $aTime <=> $bTime;
    }
    return $bTime <=> $aTime;
});

$postList = array_values($postList);
$perPage = 6;
$totalPosts = count($postList);
$totalPages = max(1, (int)ceil($totalPosts / $perPage));
$page = max(1, min($page, $totalPages));
$offset = ($page - 1) * $perPage;
$pagedPosts = array_slice($postList, $offset, $perPage);

$baseParams = ['search' => $search, 'sort' => $sort];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/app.css">
</head>
<body class="bg-light blog-page">
<?php include('components/nav.php'); ?>
<?php include('message.php'); ?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <h1 class="h3 mb-0">Latest Posts</h1>
        <?php if (isset($_SESSION['authenticated'])): ?>
            <a href="create.php" class="btn btn-primary">Create Post</a>
        <?php endif; ?>
    </div>

    <form method="get" class="row g-2 mb-4">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control" placeholder="Search by title or description" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest first</option>
                <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Oldest first</option>
            </select>
        </div>
        <div class="col-md-1 d-grid">
            <button type="submit" class="btn btn-outline-secondary">Go</button>
        </div>
    </form>

    <div class="row g-4">
        <?php if (!empty($pagedPosts)): ?>
            <?php foreach ($pagedPosts as $post): ?>
                <?php
                $description = trim((string)($post['description'] ?? ''));
                $excerpt = strlen($description) > 170 ? substr($description, 0, 170) . '...' : $description;
                $createdAtRaw = isset($post['create_date']) ? trim((string)$post['create_date']) : '';
                $createdAtTs = $createdAtRaw !== '' ? strtotime($createdAtRaw) : false;
                $createdAtLabel = $createdAtTs ? date('M d, Y', $createdAtTs) : 'Date not available';
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" alt="Post image" style="height: 220px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="card-text text-muted mb-2"><?php echo nl2br(htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8')); ?></p>
                            <small class="text-secondary mb-3">Created: <?php echo htmlspecialchars($createdAtLabel, ENT_QUOTES, 'UTF-8'); ?></small>

                            <div class="mb-2">
                                <a href="post.php?id=<?php echo (int)$post['id']; ?>" class="btn btn-sm btn-outline-secondary">View Post</a>
                            </div>

                            <?php if (isset($_SESSION['authenticated']) && isset($_SESSION['auth_user']['user_role']) && $_SESSION['auth_user']['user_role'] === 'admin'): ?>
                                <div class="mt-auto d-flex gap-2 flex-wrap">
                                    <a href="update.php?id=<?php echo (int)$post['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <form method="post" action="user/post-valid.php" onsubmit="return confirm('Delete this post?');" class="d-inline-block">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" name="delete_post" value="<?php echo (int)$post['id']; ?>">Delete</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info mb-0">No posts found. Try a different search.</div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($totalPosts > 0 && $totalPages > 1): ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-2">
            <small class="text-muted">
                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $perPage, $totalPosts); ?> of <?php echo $totalPosts; ?> posts
            </small>
            <nav aria-label="Post pagination">
                <ul class="pagination mb-0 flex-wrap justify-content-center">
                    <?php
                    $prevDisabled = $page <= 1;
                    $prevLink = '?' . http_build_query(array_merge($baseParams, ['page' => $page - 1]));
                    ?>
                    <li class="page-item <?php echo $prevDisabled ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $prevDisabled ? '#' : htmlspecialchars($prevLink, ENT_QUOTES, 'UTF-8'); ?>" tabindex="<?php echo $prevDisabled ? '-1' : '0'; ?>" aria-disabled="<?php echo $prevDisabled ? 'true' : 'false'; ?>">Previous</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php $pageLink = '?' . http_build_query(array_merge($baseParams, ['page' => $i])); ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo htmlspecialchars($pageLink, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php
                    $nextDisabled = $page >= $totalPages;
                    $nextLink = '?' . http_build_query(array_merge($baseParams, ['page' => $page + 1]));
                    ?>
                    <li class="page-item <?php echo $nextDisabled ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $nextDisabled ? '#' : htmlspecialchars($nextLink, ENT_QUOTES, 'UTF-8'); ?>" tabindex="<?php echo $nextDisabled ? '-1' : '0'; ?>" aria-disabled="<?php echo $nextDisabled ? 'true' : 'false'; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
