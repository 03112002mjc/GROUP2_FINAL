<?php
session_start();
include '../../config/config.php';
include '../../function.php';

// Ensure only admins can access this page
checkRole('admin');

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Error: An account with this email already exists.";
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
    <title>Create New User</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/admin_dashboard.css">
    <link rel="stylesheet" href="../../public/css/create-user.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-book"></i> Admin Dashboard
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle" id="user-circle"></i>
                <div class="dropdown">
                <i class="fa-solid fa-caret-down"></i>
                    <div class="dropdown-content">
                        <a href="logout.php">Logout</a>
                        <a href="../admin/admin_profile.php">Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
    <nav>
            <ul>
                <li><a href="../admin/admin_dashboard.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="../admin/admin_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                
            </ul>
        </nav>
    
    
    
    <form method="POST" action="">
    <h1><i class="fas fa-user-plus"></i> Create New User</h1>
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="student">Student</option>
            <option value="instructor">Instructor</option>
            <option value="admin">Admin</option>
        </select><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Create User</button>
        <a href="../admin/admin_dashboard.php" class="button">Back to Dashboard</a>
    </form>
    <br>
    </main>
    <footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>
</body>
</html>
