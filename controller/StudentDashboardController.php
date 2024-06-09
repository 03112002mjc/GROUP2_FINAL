<?php

class StudentDashboardController {
    private $noteModel;
    private $sub_action;

    public function __construct($conn, $sub_action) {
        $this->noteModel = new NoteModel($conn);
        $this->sub_action = $sub_action;
    }

    public function handleRequest() {
        

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
            header("Location: ../index.php");
            exit();
        }

        switch ($this->sub_action) {
            case 'add_note':
                $this->addNote();
                break;
            // Add other student dashboard actions here
            default:
                $this->viewDashboard();
                break;
        }
    }

    private function addNote() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $file_path = ''; // Handle file upload if necessary

            $this->noteModel->addNote($title, $content, $user_id, $file_path);
            header("Location: index.php?action=student_dashboard");
            exit();
        } else {
            $this->viewDashboard(); // Load the dashboard if not a POST request
        }
    }

    private function viewDashboard() {
        $user_id = $_SESSION['user_id'];
        $notes = $this->noteModel->getUserNotes($user_id);
        include "view/student_dashboard.php";
    }
}
?>
