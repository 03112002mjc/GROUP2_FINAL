<?php
include '../../config/config.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete notes associated with the user
    $delete_notes_sql = "DELETE FROM notes WHERE user_id = ?";
    $stmt_notes = $conn->prepare($delete_notes_sql);
    $stmt_notes->bind_param("i", $user_id);
    $stmt_notes->execute();
    $stmt_notes->close();

    // Delete the user
    $delete_user_sql = "DELETE FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($delete_user_sql);
    $stmt_user->bind_param("i", $user_id);

    if ($stmt_user->execute()) {
        header("Location: ../admin/admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt_user->error;
    }

    $stmt_user->close();
}

$conn->close();
?>
