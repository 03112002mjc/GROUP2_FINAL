<?php
include '../../config/config.php';


if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Fetch user details
    $sql_user = "SELECT name FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $stmt_user->bind_result($name);
    $stmt_user->fetch();
    $stmt_user->close();

    // Fetch notes for the user
    $sql_notes = "SELECT id, title, content, created_at FROM notes WHERE user_id = ?";
    $stmt_notes = $conn->prepare($sql_notes);
    $stmt_notes->bind_param("i", $user_id);
    $stmt_notes->execute();
    $result_notes = $stmt_notes->get_result();

    $notes = [];
    while ($row = $result_notes->fetch_assoc()) {
        $notes[] = $row;
    }
    $stmt_notes->close();

    echo json_encode(['name' => $name, 'notes' => $notes]);
}

$conn->close();
?>
