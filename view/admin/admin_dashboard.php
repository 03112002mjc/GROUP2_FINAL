<?php
session_start();
include '../../config/config.php';
include '../../function.php';

// Ensure only admins can access this page
checkRole('admin');

// Fetch roles for the dropdown
$sql_roles = "SELECT DISTINCT role FROM users";
$result_roles = $conn->query($sql_roles);

// Fetch selected role from the dropdown
$selected_role = isset($_GET['role']) ? $_GET['role'] : '';

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the admin's name
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();

// Fetch users based on the selected role
if ($selected_role) {
    $sql_users = "SELECT id, name, email, role FROM users WHERE role = ?";
    $stmt_users = $conn->prepare($sql_users);
    $stmt_users->bind_param("s", $selected_role);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();
} else {
    $sql_users = "SELECT id, name, email, role FROM users";
    $result_users = $conn->query($sql_users);
}

// Fetch all notes for the admin to manage
$sql_notes = "SELECT id, title, content, user_id, created_at FROM notes";
$result_notes = $conn->query($sql_notes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/admin_dashboard.css">
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

                        <a href="../user/logout.php">Logout</a>
                        <a href="admin_profile.php">Profile</a>

                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <nav class="side-nav">
            <ul>

                <li><a href="admin_dashboard.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="admin_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                
            </ul>
        </nav>
        
        <div class="main-content">
            <div class="top-container">
            <h1><i class="fas fa-users"></i> Manage Users</h1>
            <form method="GET" action="">
                <label for="role">Filter by Role:</label>
                <select name="role" id="role" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <?php while ($row_role = $result_roles->fetch_assoc()): ?>
                        <option value="<?php echo $row_role['role']; ?>" <?php if ($row_role['role'] == $selected_role) echo 'selected'; ?>>
                            <?php echo ucfirst($row_role['role']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row_user = $result_users->fetch_assoc()): ?>
                    <tr class="row-selector">
                        <td><?php echo $row_user['id']; ?></td>
                        <td><?php echo $row_user['name']; ?></td>
                        <td><?php echo $row_user['email']; ?></td>
                        <td><?php echo $row_user['role']; ?></td>
                        <td>
                            <a href="../user/edit_user.php?id=<?php echo $row_user['id']; ?>">Edit</a>
                            <a href="../user/delete_user.php?id=<?php echo $row_user['id']; ?>" onclick="return confirm('Are you sure to delete this user?')">Delete</a>
                            <a href="#" class="view-notes-link" data-user-id="<?php echo $row_user['id']; ?>">View Notes</a> 
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            </div>
            <div class="manage_notes">
                <h2 id="user-name">Manage Notes</h2>
                <table id="notes-table">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </table>
            </div>
        </div>
    </main>
    <footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>
    <script src="../../public/js/admin_dashboard.js"></script>
</body>
</html>

<?php
if (isset($stmt_users)) {
    $stmt_users->close();
}
$conn->close();
?>
