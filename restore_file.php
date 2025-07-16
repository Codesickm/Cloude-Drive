<?php
session_start();
include 'db/db_config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $file_id = intval($_GET['id']);
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("UPDATE files SET is_deleted = 0 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $file_id, $user_id);
    $stmt->execute();

    header("Location: recycle_bin.php");
    exit();
}
?>