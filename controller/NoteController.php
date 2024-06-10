<?php
// Enable error reporting for debugging


include_once __DIR__ . '/../model/NoteModel.php';
include_once __DIR__ . '/../config/config.php';

class NoteController {
    private $model;

    public function __construct() {
        global $conn; // Assuming $conn is defined in your config file
        $this->model = new NoteModel($conn);
    }

    public function handleRequest($action) {
        switch ($action) {
            case 'createNote':
                $this->createNote();
                break;
            case 'updateNote':
                $this->updateNote();
                break;
            case 'deleteNote':
                $this->deleteNote();
                break;
            case 'viewNote':
                $this->viewNote();
                break;
            case 'downloadNote':
                $this->downloadNote();
                break;
            case 'editNote':
                $this->editNote();
                break;
            default:
                echo "Error!";
                break;
        }
    }

    public function addNote() {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
            header("Location: ../index.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
       

        $this->model->addNote($user_id, $title, $content);

        header("Location: ../view/student_dashboard.php");
        exit();
    }

    private function viewNote() {
        if (isset($_GET['note_id'])) {
            $noteId = $_GET['note_id'];
            $note = $this->model->getNoteById($noteId);
            if ($note) {
                header('Content-Type: application/json');
                echo json_encode($note);
                exit();
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Note not found']);
                exit();
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Note ID not provided']);
            exit();
        }
    }
    

    public function updateNote() {
        session_start();
    
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
            header("Location: ../index.php");
            exit();
        }
    
        $note_id = $_POST['note_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];
    
        // Handle file upload logic
        
    
        $success = $this->model->updateNote($note_id, $user_id, $title, $content);
    
        if ($success) {
            header("Location: view/student_dashboard.php?success=1");
            exit();
        } else {
            echo "Error updating note.";
        }
    }
    private function deleteNote() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
            exit;
        }

        $noteId = $_POST['note_id'];
        $dashboard = $_POST['dashboard'] ?? '';

        

        if ($this->model->deleteNoteById($noteId)) {
            if ($dashboard === 'admin') {
                header("Location: ../view/admin_dashboard.php");
            } else {
                header("Location: ../view/student_dashboard.php");
            }
            exit();
        } else {
            echo "Error: Unable to delete note.";
        }
    }

    
    public function downloadNote() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
            exit;
        }

        if (isset($_GET['note_id'])) {
            $noteId = $_GET['note_id'];
            $userId = $_SESSION['user_id'];

            $note = $this->model->getNoteByIdAndUserId($noteId, $userId);

            if ($note['title'] && $note['content']) {
                // Set headers to download the file
                header('Content-Type: text/plain');
                header('Content-Disposition: attachment; filename="' . $note['title'] . '.txt"');
                echo "Title: " . $note['title'] . "\n\n";
                echo "Content:\n" . $note['content'];
            } else {
                echo "Note not found or you do not have permission to download this note.";
            }
        } else {
            echo "Invalid request.";
        }
    }

    public function editNote() {
        session_start();
    
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $note_id = $_GET['id'];
            $note = $this->model->getNoteById($note_id);
    
            // Check if the note exists
            if ($note) {
                $title = $note['title'];
                $content = $note['content'];
                include_once __DIR__ . '../view/edit_note.php';
            } else {
                // Handle case where note is not found
                echo "Note not found.";
            }
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $note_id = $_POST['id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $this->model->updateNoteById($note_id, $title, $content);
    
            // Redirect back to edit_note.php with a success message
            echo "<script>alert('Note updated successfully!'); window.location.href = '../view/admin_dashboard.php';</script>";
            exit();
        }
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

    public function getNotesByUserId($user_id) {
        return $this->model->getNotesByUserId($user_id);
    }

    public function getNoteByIdAndUserId($note_id, $user_id) {
        return $this->model->getNoteByIdAndUserId($note_id, $user_id);
    }

    public function viewNoteByIdAndUserId($note_id, $user_id) {
        $noteDetails = $this->model->getNoteByIdAndUserId($note_id, $user_id);
        echo json_encode($noteDetails);
    }
}

// Instantiate the controller
$noteController = new NoteController();
if (isset($_GET['action'])) {
    $noteController->handleRequest($_GET['action']);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
    $noteController->addNote();
}
?>
