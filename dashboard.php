<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include 'db/db_config.php';
$user_id = $_SESSION["user_id"];

if (!empty($_GET['filter_folder'])) {
    $fid = intval($_GET['filter_folder']);
    $result = $conn->query("SELECT * FROM files WHERE user_id = $user_id AND folder_id = $fid AND is_deleted = 0 ORDER BY uploaded_at DESC");
} else {
    $result = $conn->query("SELECT * FROM files WHERE user_id = $user_id AND is_deleted = 0 ORDER BY uploaded_at DESC");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>MohitDrive Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background-color: blue;
        }
    </style>
</head>

<body>
    <div class="text-end p-3">
        <button class="btn btn-sm btn-secondary" onclick="toggleTheme()">🌗 Toggle Dark Mode</button>
    </div>

    <div class="container mt-4">
        <h2 class="mb-4">Welcome,
            <?php echo $_SESSION["username"]; ?> 👋
        </h2>
        <!-- Folder Creation Form -->
        <h4>Create New Folder</h4>
        <form method="POST" action="create_folder.php" class="mb-4">
            <input type="text" name="folder_name" placeholder="Enter folder name..." class="form-control mb-2" required>
            <button type="submit" class="btn btn-primary">Create Folder</button>
        </form>

        <?php
    $folders = $conn->query("SELECT * FROM folders WHERE user_id = $user_id");
    ?>

        <h4>Upload File</h4>
        <form method="POST" action="upload.php" enctype="multipart/form-data" class="mb-4">
            <div class="mb-3">
                <input type="file" name="file" class="form-control" required>
            </div>

            <div class="mb-3">
                <select name="folder_id" class="form-select">
                    <option value="">Unsorted</option>
                    <?php while ($f = $folders->fetch_assoc()): ?>
                    <option value="<?php echo $f['id']; ?>">
                        <?php echo htmlspecialchars($f['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Upload</button>
        </form>

        
        <form method="GET" class="mb-4">
            <label for="filter_folder" class="form-label">Filter by Folder:</label>
            <select name="filter_folder" id="filter_folder" class="form-select" onchange="this.form.submit()">
                <option value="">All Folders</option>
                <?php
            $folderList = $conn->query("SELECT * FROM folders WHERE user_id = $user_id");
            while ($row = $folderList->fetch_assoc()):
            ?>
                <option value="<?php echo $row['id']; ?>" <?php if (!empty($_GET['filter_folder']) &&
                    $_GET['filter_folder']==$row['id']) echo 'selected' ; ?>>
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </form>




        <div class="row">
            <?php while ($file = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title">
                            <?php echo $file['filename']; ?>
                        </h6>
                        <p class="card-text">
                            <?php echo round($file['filesize'] / 1024, 2); ?> KB<br>
                            <?php
                                $ext = pathinfo($file['filename'], PATHINFO_EXTENSION);
                                echo strtoupper($ext) . " file";
                            ?>
                        </p>

                        <a href="?preview=<?php echo $file['id']; ?>" class="btn btn-sm btn-outline-info">Preview</a>
                        <a href="<?php echo $file['filepath']; ?>" download
                            class="btn btn-sm btn-outline-success">Download</a>
                        <a href="delete_file.php?id=<?php echo $file['id']; ?>" class="btn btn-sm btn-outline-danger">🗑
                            Delete</a>


                        <?php if (empty($file['share_token'])): ?>
                        <a href="generate_share.php?id=<?php echo $file['id']; ?>"
                            class="btn btn-sm btn-outline-primary mt-2">Generate Link</a>
                        <?php else: ?>
                        <a href="share.php?token=<?php echo $file['share_token']; ?>"
                            class="btn btn-sm btn-outline-dark mt-2" target="_blank">Public Link</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="recycle">
            <a href="recycle_bin.php" class="btn btn-sm btn-secondary mt-3">🗑️ View Recycle Bin</a>
        </div>
        <div class="text-end mt-4">
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </div>

    </div>




    <?php
    if (isset($_GET['preview'])) {
        $preview_id = intval($_GET['preview']);
        $preview_query = $conn->query("SELECT * FROM files WHERE id = $preview_id AND user_id = $user_id");

        if ($preview_query->num_rows === 1) {
            $file = $preview_query->fetch_assoc();
            $file_url = $file['filepath'];
            $file_type = $file['filetype'];

            echo "<h3>Preview: {$file['filename']}</h3>";

            if (str_starts_with($file_type, 'image/')) {
                echo "<img src='$file_url' style='max-width:500px;'>";
            } elseif ($file_type === 'application/pdf') {
                echo "<iframe src='$file_url' width='100%' height='500px'></iframe>";
            } elseif (str_starts_with($file_type, 'video/')) {
                echo "<video width='500' controls><source src='$file_url' type='$file_type'>Your browser doesn't support video tag.</video>";
            } else {
                echo "<p>Preview not supported. <a href='$file_url' download>Download instead</a>.</p>";
            }
        } else {
            echo "<p>Invalid file or permission denied.</p>";
        }
    }
    ?>



    <script>
        function toggleTheme() {
            document.body.classList.toggle("bg-dark");
            document.body.classList.toggle("text-white");

            let cards = document.querySelectorAll(".card");
            cards.forEach(card => card.classList.toggle("bg-dark"));
        }
    </script>

</body>

</html>