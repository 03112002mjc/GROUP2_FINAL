<?php
session_start();
include '../../config/config.php';
include '../../function.php';

// Ensure only admins can access this page
checkRole('admin');

if (isset($_GET['id'])) {
    $note_id = $_GET['id'];

    // Delete the note
    $sql = "DELETE FROM notes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $note_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../admin/admin_dashboard.php");
    exit();
}
?>
