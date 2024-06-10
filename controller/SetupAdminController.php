<?php
session_start();
include "../model/UserModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $userModel = new UserModel();
    $result = $userModel->setupAdmin($name, $email, $password);

    if ($result === "Admin account created successfully!") {
        echo "<script>alert('Admin account created successfully!'); window.location.href = '../index.php';</script>";
        exit;
    } else {
        header("Location: ../view/setup_admin.php?error=" . urlencode($result));
        exit;
    }
}
?>
