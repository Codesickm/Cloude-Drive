<?php
session_start();
include 'db/db_config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$result = $conn->query("SELECT * FROM files WHERE user_id = $user_id AND is_deleted = 1 ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Recycle Bin - MohitDrive</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container">
        <h2>🗑️ Recycle Bin</h2>
        <ul>
            <?php while ($file = $result->fetch_assoc()): ?>
            <li>
                <?php echo $file['filename']; ?>
                (
                <?php echo round($file['filesize'] / 1024, 2); ?> KB) |
                <a href="restore_file.php?id=<?php echo $file['id']; ?>">♻️ Restore</a> |
                <a href="permanent_delete.php?id=<?php echo $file['id']; ?>"
                    onclick="return confirm('Are you sure to delete forever?')">❌ Delete Forever</a>
            </li>
            <?php endwhile; ?>
        </ul>
        <br><a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>

</html>