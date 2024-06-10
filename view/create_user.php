<?php

include_once '../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New User</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/admin_dashboard.css">
    <link rel="stylesheet" href="../public/css/create-user.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-book"></i> Admin Dashboard
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle" id="user-circle"></i>
                <span class="username"><?php echo htmlspecialchars($admin_name); ?></span>
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
    <nav>
            <ul>
                <li><a href="../view/admin_dashboard.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="../view/admin_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                
            </ul>
        </nav>
    
    
    
    <form method="POST" action="">
    <h1><i class="fas fa-user-plus"></i> Create New User</h1>
    <?php if (!empty($success_message)): ?>
                <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="message error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select><br>
        <div class="user-box">
                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required />
                    <span class="password-toggle-icon"><i class="fas fa-eye"></i></span>
                </div>
            </div><br>
        <button type="submit">Create User</button>
        <a href="../view/admin_dashboard.php" class="button">Back to Dashboard</a>
    </form>
    <br>
    </main>
    <footer id="footer">
        <p>
            <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
        </p>
    </footer>
    <script src="../public/js/login.js"></script>
</body>
</html>
