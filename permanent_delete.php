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

    // Get file path before deletion
    $stmt = $conn->prepare("SELECT filepath FROM files WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $file_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $file = $result->fetch_assoc();
        $filepath = $file['filepath'];

        if (file_exists($filepath)) {
            unlink($filepath); // delete the file from disk
        }

        $conn->query("DELETE FROM files WHERE id = $file_id AND user_id = $user_id");
    }

    header("Location: recycle_bin.php");
    exit();
}
?>