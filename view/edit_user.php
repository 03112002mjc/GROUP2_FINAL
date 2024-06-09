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
    <title>Edit User</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/admin_dashboard.css">
    <link rel="stylesheet" href="../public/css/edit_user.css">
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
        <nav>
            <ul>
                <li><a href="../view/admin_dashboard.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="../view/admin_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                
            </ul>
        </nav>
        <div class="main-content">
            <div class="form-container">
                <h1><i class="fas fa-user-edit"></i> Edit User</h1>
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (isset($success_message)): ?>
                    <div class="success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                <form action="../controller/UserController.php?action=updateUser" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role; ?>" <?php echo ($user['role'] === $role) ? 'selected' : ''; ?>>
                                <?php echo ucfirst($role); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Update User</button>
                    <div class="createBtn">
                        <a href="../controller/UserController.php?action=createUser" class="button">Create New User</a> <!-- Add this button -->
                        <a href="../view/admin_dashboard.php" class="button">Back to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>
</body>
</html>
