<?php

include_once __DIR__ . '/../model/UserModel.php';
include_once __DIR__ . '/../config/config.php';

class UserController {
    private $model;

    public function __construct() {
        global $conn; // Assuming $conn is defined in your config file
        $this->model = new UserModel($conn);
    }
    public function handleRequest($action) {
        switch ($action) {
            case 'editUser':
                $this->editUser();
                break;
            case 'updateUser':
                $this->updateUser();
                break;
            case 'createUser':
                $this->createUser();
                break;
            case 'deleteUser':
                $this->deleteUser();
                break;
            case 'logout':
                $this->logout();
                break;
            default:
                echo "Error!";
                break;
        }
    }
    public function createUser() {
        session_start();
        if ($_SESSION['user_role'] !== 'admin') {
            header("Location: ../index.php?error=Unauthorized access");
            exit();
        }

        $errors = [];
        $success_message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $password = md5($_POST['password']);

            if ($this->model->checkEmailExists($email)) {
                $errors[] = "Error: An account with this email already exists.";
            } else {
                if ($this->model->createUser($name, $email, $role, $password)) {
                    $success_message = "New user created successfully!";
                } else {
                    $errors[] = "Error: Unable to create user.";
                }
            }   
        }

        // Fetch roles for the dropdown
        $roles = $this->model->getRoles();
        $admin_name = $this->model->getUserNameById($_SESSION['user_id']);

        include '../view/create_user.php';
    }

    private function editUser() {
        session_start();
        if ($_SESSION['user_role'] !== 'admin') {
            header("Location: ../index.php?error=Unauthorized access");
            exit();
        }

        if (isset($_GET['id'])) {
            $user_id = $_GET['id'];
            $user = $this->model->getUserById($user_id);
            if ($user) {
                $roles = $this->model->getRoles();
                $name = $this->model->getUserNameById($_SESSION['user_id']);
                include '../view/edit_user.php';
            } else {
                header("Location: ../view/edit_user.php");
                exit();
            }
        } else {
            header("Location: ../view/edit_user.php");
            exit();
        }
    }

    private function updateUser() {
        session_start();
        if ($_SESSION['user_role'] !== 'admin') {
            header("Location: ../index.php?error=Unauthorized access");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];

            if ($this->model->updateUser($user_id, $name, $email, $role)) {
                echo "<script>alert('Account updated successfully!'); window.location.href = '../view/admin_dashboard.php';</script>";
            } else {
                $errors[] = "Error: Could not update user.";
                $user = $this->model->getUserById($user_id);
                $roles = $this->model->getRoles();
                include '../view/admin/edit_user.php';
            }
        } else {
            header("Location: ../view/admin_dashboard.php");
            exit();
        }
    }

    public function deleteUser() {
        session_start();
        if (isset($_GET['id'])) {
            $user_id = $_GET['id'];
            if ($this->model->deleteUserAndNotes($user_id)) {
                // Redirect to admin dashboard with success message
                header("Location: ../view/admin_dashboard.php?delete_success=1");
            } else {
                // Redirect to admin dashboard with error message
                header("Location: ../view/admin_dashboard.php?delete_error=1");
            }
            exit();
        }
    }
    

    public function logout(){
        session_start();

        session_unset();
        session_destroy();

        header("Location: ../index.php");
    }
}
$userController = new UserController();
if (isset($_GET['action'])) {
    $userController->handleRequest($_GET['action']);
}
?>