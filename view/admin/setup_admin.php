<?php
include '../../config/config.php';

$errors = [];

// Check if there are any admin accounts
$admin_exists = false;
$check_admin_sql = "SELECT id FROM users WHERE role = 'admin' LIMIT 1";
$check_admin_result = $conn->query($check_admin_sql);
if ($check_admin_result->num_rows > 0) {
    $admin_exists = true;
}

if ($admin_exists) {
    echo "Admin account already exists. This setup page is disabled.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'admin'; // Hardcoded to create an admin account

    // Check if the email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $errors[] = "This account already exists. Please create a new one!";
    } else {
        // Insert new user
        $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Admin account created successfully!'); window.location.href = '../../index.php';</script>";
            exit;
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/register.css">
</head>
<body>
<div class="register-container">
    <h1>Setup Admin</h1>
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
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <div class="pass-container">
            <input type="password" id="password" name="password" oninput="toggleEyeIcon()" placeholder="Password" required>
            <i id="eyeIcon" class="fas fa-eye" style="display: none;" onclick="togglePasswordVisibility()"></i>
        </div>
        <br>
        <button type="submit">Register</button>
    </form>
</div>
<footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>
<script src="../../public/js/register.js"></script>
</body>
</html>
