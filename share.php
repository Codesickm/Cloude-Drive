<?php
include 'db/db_config.php';

if (!isset($_GET['token'])) {
    die("Invalid link.");
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT * FROM files WHERE share_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("File not found or link invalid.");
}

$file = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Shared File -
        <?php echo htmlspecialchars($file['filename']); ?>
    </title>
</head>

<body>
    <h2>Shared File:
        <?php echo $file['filename']; ?>
    </h2>
    <p>Size:
        <?php echo round($file['filesize'] / 1024, 2); ?> KB
    </p>

    <?php
    $file_url = $file['filepath'];
    $file_type = $file['filetype'];

    if (str_starts_with($file_type, 'image/')) {
        echo "<img src='$file_url' style='max-width:600px;'>";
    } elseif ($file_type === 'application/pdf') {
        echo "<iframe src='$file_url' width='100%' height='500px'></iframe>";
    } elseif (str_starts_with($file_type, 'video/')) {
        echo "<video width='600' controls><source src='$file_url' type='$file_type'></video>";
    } else {
        echo "<p>Preview not supported.</p>";
    }
    ?>

    <br><br>
    <a href="<?php echo $file_url; ?>" download>📥 Download File</a>
</body>

</html>