<?php
session_start();
include "../model/userModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $userModel = new UserModel();
    $userModel->register($name, $email, $password);
}
?>
