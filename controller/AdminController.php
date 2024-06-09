<?php
class AdminController {
    private $userModel;
    private $noteModel;
    private $sub_action;

    public function __construct($conn, $sub_action) {
        $this->userModel = new UserModel($conn);
        $this->noteModel = new NoteModel($conn);
        $this->sub_action = $sub_action;
    }

    public function handleRequest() {
        switch ($this->sub_action) {
            case 'view_notes':
                $this->viewNotes();
                break;
            default:
                $this->dashboard();
                break;
        }
    }

    public function dashboard() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
            exit;
        }

        $this->checkRole('admin');

        $user_id = $_SESSION['user_id'];
        $selected_role = isset($_GET['role']) ? $_GET['role'] : '';

        $name = $this->userModel->getUserNameById($user_id);
        $roles = $this->userModel->getDistinctRoles();
        $users = $this->userModel->getUsersByRole($selected_role);
        $notes = $this->noteModel->getAllNotes();

        include '../view/admin_dashboard.php';
    }
    private function deleteNote() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
            exit;
        }

        $noteId = $_POST['note_id'];
        $dashboard = $_POST['dashboard'] ?? '';

        if ($dashboard === 'admin') {
            include '../function.php';
            checkRole('admin');
        }

        if ($this->model->deleteNoteById($noteId)) {
            if ($dashboard === 'admin') {
                header("Location: ../view/admin_dashboard.php");
            } else {
                header("Location: ../view/student_dashboard.php");
            }
            exit;
        } else {
            echo "Error: Unable to delete note.";
        }
    }
}
?>
