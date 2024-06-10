<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Notebook</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../public/css/register.css">
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="../public/img/CITE.png" alt="">
        </div>
        <h1>Setup Admin</h1>
        <p id="fill-up">Fill the following Form</p>

        <?php
        include_once "../model/UserModel.php";
        $userModel = new UserModel();
        $check_admin_result = $userModel->checkAdminExists();
        
        if ($check_admin_result) {
            // Admin exists, disable setup
            echo "<p class='message error'>Admin account already exists. This setup page is disabled.</p>";
        } else {
            // Admin does not exist, show setup form
            if (isset($_GET['error'])) {
                echo "<p class='message error'>" . htmlspecialchars($_GET['error']) . "</p>";
            }
        ?>
        <form action="../controller/SetupAdminController.php" method="post">
            <label>Name</label>
            <input type="text" name="name" placeholder="ex. Juan S. Dela Cruz" required><br>

            <label>Email</label>
            <input type="email" name="email" placeholder="xxx@gmail.com" required><br>

            <div class="user-box">
                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required />
                    <span class="password-toggle-icon"><i class="fas fa-eye"></i></span>
                </div>
            </div><br>

            <button type="submit">Create Admin</button>
        </form>
        <?php } ?>
    </div>
    <div class="register-container2">
        <p class="text-muted text-center"><small>Go Back?</small></p>
        <a href="../index.php" class="btn btn-default btn-block">Login</a>
    </div>
    <footer id="footer">
        <p>
            <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
        </p>
    </footer>
    <script src="../public/js/login.js"></script>
</body>
</html>
