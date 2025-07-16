<?php
session_start();
include 'db/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["folder_name"])) {
    $folder = trim($_POST["folder_name"]);
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("INSERT INTO folders (user_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $folder);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>