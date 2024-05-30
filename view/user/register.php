<?php
include '../../config/config.php';

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Default role as 'student'
    $role = 'student';

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "An account with this email already exists.";
    } else {
        $sql = "INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $role, $password);

        if ($stmt->execute()) {
            $success_message = "New user created successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/register.css">
</head>
<body>
<div class="register-container">
<div class="logo">
        <img src="../../public/img/CITE.png" alt="">
    </div>
    <h1>Register</h1>
    <p id="fill-up">Fill the following Form</p>
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="ex. Juan S. Dela Cruz" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="xxx@gmail.com" required>
        <br>
        <label for="password">Password:</label>
        <div class="pass-container">
            <input type="password" id="password" name="password" placeholder="Password" oninput="toggleEyeIcon()" placeholder="Password" required>
            <i id="eyeIcon" class="fas fa-eye" style="display: none;" onclick="togglePasswordVisibility()"></i>
        </div>
        <br>
        <button type="submit">Register</button>
    </form>
</div>
<div class="register-container2">
    <p class="text-muted text-center"><small>Already have an account?</small></p>
    <a href="../../index.php" class="btn btn-default btn-block">Login</a>
</div>
<footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com"><small>Group2</small></a><br> WebDev</small>
    </p>
</footer>
<script src="../../public/js/login.js"></script>
</body>
</html>
