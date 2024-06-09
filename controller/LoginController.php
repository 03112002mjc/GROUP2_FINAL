<?php
include_once "model/UserModel.php";

class LoginController {
    public function handleRequest() {
        $userModel = new UserModel();
        $admin_exists = $userModel->checkAdminExists();

        // Check if the login form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'login') {
            $this->login($userModel);
        } else {
            // Display the login form
            include_once "view/login.php";
        }
    }

    private function login($userModel) {
        // Validate username and password
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";

        if (empty($email) || empty($password)) {
            // Redirect back to login page with error message if username or password is empty
            header("Location: ../index.php?error=Username and password are required.");
            exit();
        } else {
            // Attempt login
            $userModel->login($email, $password);
        }
    }
}

$loginController = new LoginController();
$loginController->handleRequest();
?>
