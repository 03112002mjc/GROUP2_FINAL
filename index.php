<?php
session_start();
include 'config/config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists
    $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $email, $hashed_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start a new session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;

            // Redirect to the appropriate dashboard based on role
            if ($role == 'admin') {
                header("Location: view/admin/admin_dashboard.php");
            } else {
                header("Location: view/user/dashboard.php");
            }
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    } else {
        $errors[] = "Invalid email or password.";
    }

    $stmt->close();
}

// Check if there are any admin accounts
$admin_exists = false;
$check_admin_sql = "SELECT id FROM users WHERE role = 'admin' LIMIT 1";
$check_admin_result = $conn->query($check_admin_sql);
if ($check_admin_result->num_rows > 0) {
    $admin_exists = true;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
    <h3>Login to continue</h3>
    <div class="error-container">
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<p class="error-message">' . $error . '</p>';
            }
        }
        ?>
    </div>
    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="example@mail.com" required>
        <br>
        <label for="password">Password:</label>
        <div class="pass-container">
            <input type="password" id="password" name="password" oninput="toggleEyeIcon()" placeholder="Password" required>
            <i id="eyeIcon" class="fas fa-eye" style="display: none;" onclick="togglePasswordVisibility()"></i>
        </div>
        <button type="submit">Login</button>
    </form>
    <?php if (!$admin_exists): ?>
        <div class="setup-admin-link">
            <p>No admin account found. <a href="view/admin/setup_admin.php">Setup Admin Account</a></p>
        </div>
    <?php endif; ?>
</div>
<div class="container login-container2">
    <p class="text-muted text-center"><small>Do not have an account?</small></p>
    <a href="view/user/register.php" class="btn btn-default btn-block"><small>Create an account</small></a>
</div>
<footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>
<script src="public/js/login.js"></script>
</body>
</html>
