<?php
// include('../config/app.php');
include('../auth/authentication.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SL Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/app.css">
</head>
<body class="bg-light auth-page">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="text-center mb-3">
                <a href="../index.php" class="text-decoration-none fw-bold">SL Blog</a>
            </div>

            <?php include('../message.php'); ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">Create account</h1>
                    <p class="text-muted small mb-4">Join and start sharing your blog posts.</p>

                    <form action="" method="post" novalidate>
                        <input type="hidden" name="role" value="user">

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="name" type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" class="form-control" minlength="6" placeholder="At least 6 characters" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input id="confirm_password" type="password" name="confirm_Password" class="form-control" minlength="6" placeholder="Re-enter your password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" name="regstar_btn">Register</button>
                            <a href="login.php" class="btn btn-outline-secondary">Already have an account? Sign in</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>