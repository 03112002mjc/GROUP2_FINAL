<?php
session_start();
include_once '../config/config.php';
include_once '../controller/NoteController.php';


// Check if the user is an admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php?error=Unauthorized access");
    exit();
}

// Fetch user's name
$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

if (isset($_GET['id'])) {
    $note_id = $_GET['id'];
    
    // Fetch note details
    $sql = "SELECT title, content FROM notes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $note_id);
    $stmt->execute();
    $stmt->bind_result($title, $content);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $note_id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Update note details
    $sql = "UPDATE notes SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $note_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../admin/admin_dashboard.php");
    exit();
}
$success = isset($_GET['success']) && $_GET['success'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Note</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/admin_dashboard.css">
    <link rel="stylesheet" href="../public/css/edit_note.css">
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
                <li><a href="../view/admin_dashboard.php"><i class="fas fa-users"></i> Users</a></li>
                
            </ul>
        </nav>
        
        <div class="main-content">
            <div class="top-container">
                <h1><i class="fas fa-sticky-note"></i> Edit Note</h1>
                <?php if ($success): ?>
            <div class="alert success" role="alert">
                Note updated successfully.
            </div>
            <?php endif; ?>
                <form method="POST" action="../controller/NoteController.php?action=editNote">
                    <input type="hidden" name="id" value="<?php echo $note_id; ?>">
                    <label for="title">Title:</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
                    <label for="content">Content:</label>
                    <textarea name="content"><?php echo htmlspecialchars($content); ?></textarea>
                    <button type="submit">Update Note</button>
                </form>
            </div>
        </div>
    </main>
    <footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>

<script src="../public/js/edit_note.js"></script>
</body>
</html>