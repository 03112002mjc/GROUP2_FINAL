<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../controller/NoteController.php';
include_once __DIR__ . '/../model/NoteModel.php';

$noteController = new NoteController();

// Check if user is logged in and has student role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

// Fetch user's name
$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

$notes = $noteController->getNotesByUserId($_SESSION['user_id']);

// Close any open result sets and database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Notebook</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="header-container">
        <h1 class="logo"><i class="fa-solid fa-book-open"></i> My Notebook</h1>
        <div class="user-info">
            <i class="fa-solid fa-circle-user" id="user-circle"></i>
            <span class="username"><?php echo htmlspecialchars($name); ?></span>
            <div class="dropdown">
                <button class="dropbtn"><i class="fa-solid fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href="../controller/UserController.php?action=logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>
<main>
    <div class="main-content">
        <div class="top-container" id="top-container">
            <div class="h1_container">
                <h1><i class="fa-solid fa-pencil"></i> Notes</h1>
            </div>
        </div>
        <div class="note-form-container">
            <form method="post" action="../controller/NoteController.php" class="note-form" id="note-form" enctype="multipart/form-data">
                <input type="hidden" id="note-id" name="note_id">
                <h1>Add Note</h1>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                <br>
                <label for="content">Content:</label>
                <textarea id="content" name="content" required></textarea>
                <br>
                <button type="submit" id="note-button"><i class="fas fa-circle-plus"></i> Note</button><br>
            </form>
        </div>
        <div class="notes-container">
            <h1>Notes Details</h1>
            <div class="notes" id="myNotes">
                <?php if (!empty($notes)): ?>
                    <?php foreach ($notes as $note): ?>
                        <div class="note" id="note-<?php echo $note['id']; ?>">
                            <h2><?php echo htmlspecialchars($note['title']); ?></h2>
                            <small><?php echo htmlspecialchars($note['created_at']); ?></small><br>
                            <?php if (isset($note['file_path']) && !empty($note['file_path'])): ?>
                                <a href="<?php echo htmlspecialchars($note['file_path']); ?>" download>Download Attachment</a>
                            <?php endif; ?>
                            <div class="note-buttons">
                                <button onclick="viewNote(<?php echo $note['id']; ?>)" class="icon-button"><i class="fa-solid fa-eye"></i></button>
                                <button type="button" class="icon-button edit-button" data-id="<?php echo $note['id']; ?>" data-title="<?php echo htmlspecialchars($note['title']); ?>" data-content="<?php echo htmlspecialchars($note['content']); ?>"><i class="fa-solid fa-edit"></i></button>
                                <form action="../controller/NoteController.php?action=deleteNote" method="post" style="display:inline;">
                                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                    <input type="hidden" name="dashboard" value="user">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this note?');" class="icon-button"><i class="fa-solid fa-trash"></i></button>
                                </form>
                                <a href="../controller/NoteController.php?action=downloadNote&note_id=<?php echo $note['id']; ?>" class="icon-button"><i class="fa-solid fa-cloud-arrow-down"></i> Download</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No notes found. Create your first note here!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>

<script src="../public/js/dashboard.js"></script>
</body>
</html>
