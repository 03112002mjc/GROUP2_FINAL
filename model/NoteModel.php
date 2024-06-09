<?php
include __DIR__ . "/../config/config.php";


class NoteModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    

    public function getNoteByIdAndUserId($noteId, $userId) {
        $sql = "SELECT title, content FROM notes WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $noteId, $userId);
        $stmt->execute();
        $stmt->bind_result($title, $content);
        $stmt->fetch();
        $note = ['title' => $title, 'content' => $content];
        $stmt->close();
        return $note;
    }

    public function getNotesByUserId($user_id) {
        $sql = "SELECT id, title, content, created_at FROM notes WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $notes = [];
        while ($row = $result->fetch_assoc()) {
            $notes[] = $row;
        }
        $stmt->close();
        return $notes;
    }
    public function updateNoteById($note_id, $title, $content) {
        $sql = "UPDATE notes SET title = ?, content = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $note_id);
        $stmt->execute();
        $stmt->close();
    }

    public function getNoteById($note_id) {
        $sql = "SELECT title, content FROM notes WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $note_id);
        $stmt->execute();
        $stmt->bind_result($title, $content);
        $stmt->fetch();
        $note = ['title' => $title, 'content' => $content];
        $stmt->close();
        return $note;
    }

    public function addNote($user_id, $title, $content) {
        $sql = "INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $title, $content);
        $stmt->execute();
        $stmt->close();
    }


    public function deleteNoteById($noteId) {
        $sql = "DELETE FROM notes WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $noteId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    
}

?>
