<?php
session_start();
include '../config/config.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the admin's current details
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// Update profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'] ? md5($_POST['password'], PASSWORD_BCRYPT) : null;

    if ($new_password) {
        $sql_update = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssi", $new_name, $new_email, $new_password, $user_id);
    } else {
        $sql_update = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $new_name, $new_email, $user_id);
    }

    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully.";
    } else {
        $_SESSION['error_message'] = "Error updating profile.";
    }
    $stmt_update->close();
    header("Location: admin_profile.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/admin_dashboard.css">
    <link rel="stylesheet" href="../public/css/admin_profile.css">

</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-book"></i> Admin Dashboard
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle" id="user-circle"></i>
                <span class="username"><?php echo htmlspecialchars($name); ?></span>
                <div class="dropdown">
                <i class="fa-solid fa-caret-down"></i>
                    <div class="dropdown-content">
                        <a href="../controller/UserController.php?action=logout">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <nav class="side-nav">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="../controller/UserController.php?action=createUser"><i class="fas fa-user"></i>Create User</a></li>
                
                
                
            </ul>
        </nav>
        <div class="main-content">
            <div class="top-container">
            <h1> Admin Profile</h1>
                <form method="POST" action="">
                <i class="fas fa-circle-user" id="admin_top"></i>
                <?php if (isset($_SESSION['success_message'])) : ?>
                    <div class="message success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error_message'])) : ?>
                    <div class="message error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    
                    <div class="user-box">
                        <label for="password">Password (leave blank to keep current):</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" placeholder="Password" required />
                            <span class="password-toggle-icon"><i class="fas fa-eye"></i></span>
                        </div>
                    </div><br>
                    <button type="submit">Update Profile</button>
                </form>
            </div>
        </div>
    </main>
    <footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>
<script src="../public/js/login.js"></script>
</body>
</html>
