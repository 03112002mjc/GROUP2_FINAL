<?php
session_start();
include '../../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

$note_id = $_POST['note_id'];
$dashboard = $_POST['dashboard'] ?? '';

// Check if the request is coming from an admin
if ($dashboard === 'admin') {
    include '../../function.php';
    checkRole('admin');
}

// Delete the note
$sql = "DELETE FROM notes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $note_id);

if ($stmt->execute()) {
    if ($dashboard === 'admin') {
        header("Location: ../admin/admin_dashboard.php");
    } else {
        header("Location: ../user/dashboard.php");
    }
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
