<?php
session_start();
include 'db/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $file_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // Generate a unique token
    $token = bin2hex(random_bytes(16)); // 32 characters

    $stmt = $conn->prepare("UPDATE files SET share_token = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $token, $file_id, $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>