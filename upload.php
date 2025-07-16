<?php

$folder_id = !empty($_POST["folder_id"]) ? intval($_POST["folder_id"]) : null;



session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION["user_id"];


include 'db/db_config.php';
$folder_id = !empty($_POST["folder_id"]) ? intval($_POST["folder_id"]) : null;
$upload_root = "files/user_$user_id/";
$upload_dir = $upload_root;

if ($folder_id) {
    // Get folder name from DB
    $stmt = $conn->prepare("SELECT name FROM folders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $folder_id, $user_id);
    $stmt->execute();
    $folder_result = $stmt->get_result();

    if ($folder_result->num_rows === 1) {
        $folder_name = $folder_result->fetch_assoc()["name"];
        $safe_folder = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $folder_name); // sanitize
        $upload_dir .= $safe_folder . "/";
    }
}

// Create directory if needed
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
   $filename = basename($_FILES["file"]["name"]);
$target_path = $upload_dir . $filename;
$tmp_path = $_FILES["file"]["tmp_name"];

$filetype = mime_content_type($tmp_path);
$filesize = $_FILES["file"]["size"];

if (move_uploaded_file($tmp_path, $target_path)) {
    $stmt = $conn->prepare("INSERT INTO files (user_id, filename, filepath, filetype, filesize, folder_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssii", $user_id, $filename, $target_path, $filetype, $filesize, $folder_id);
    $stmt->execute();

    header("Location: dashboard.php?upload=success");
    exit();
} else {
    echo "❌ Upload failed. Check permissions or directory.";
}

}
?>