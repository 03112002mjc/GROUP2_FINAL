<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/model/UserModel.php';
require_once __DIR__ . '/model/NoteModel.php';
require_once __DIR__ . '/controller/LoginController.php';
require_once __DIR__ . '/controller/StudentDashboardController.php';
require_once __DIR__ . '/controller/AdminController.php';


// Determine the action and instantiate the appropriate controller
$action = isset($_GET['action']) ? $_GET['action'] : '';
$sub_action = isset($_GET['sub_action']) ? $_GET['sub_action'] : '';

switch ($action) {
    case 'student_dashboard':
        $controller = new StudentDashboardController($conn, $sub_action);
        break;
    case 'admin_dashboard':
        $controller = new AdminController($conn, $sub_action);
        break;
    default:
        $controller = new LoginController($conn);
        break;
}

$controller->handleRequest();
?>
