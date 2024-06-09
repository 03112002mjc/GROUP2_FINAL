<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Notebook</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body class="body-container">
    <div class="header-container">
        <h1>My Notebook</h1>
    </div>
    <div class="container login-container">
        <div class="logo">
            <img src="public/img/CITE.png" alt="">
        </div>
        <h3>Login to continue</h3>
        <div class="error-container">
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
        </div>
    
        <form action="index.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="example@mail.com" required>
            <br>
            <div class="user-box">
                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required />
                    <span class="password-toggle-icon"><i class="fas fa-eye"></i></span>
                </div>
            </div><br>
            <input type="hidden" name="action" value="login">
            <button type="submit" class="login-btn">Login</button>
        </form>
        <?php if (!$admin_exists): ?>
            <div class="setup-admin-link">  
                <p>No admin account found. <a href="view/setup_admin.php">Setup Admin Account</a></p>
            </div>
        <?php endif; ?>
    </div>
    <div class="container login-container2">
        <p class="text-muted text-center"><small>Do not have an account?</small></p>
        <a href="view/register.php" class="btn btn-default btn-block"><small>Create an account</small></a>
    </div>
    <footer id="footer">
        <p>
            <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
        </p>
    </footer>
<script src="public/js/login.js"></script>
</body>
</html>
