<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">SL Blog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <?php if (isset($_SESSION['authenticated'])): ?>
                    <li class="nav-item"><a class="nav-link" href="create.php">Create Post</a></li>
                    <?php if (isset($_SESSION['auth_user']['user_role']) && $_SESSION['auth_user']['user_role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['authenticated'])): ?>
                    <li class="nav-item"><span class="nav-link disabled"><?php echo htmlspecialchars($_SESSION['auth_user']['user_name'], ENT_QUOTES, 'UTF-8'); ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="login/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
