<?php
session_start();
include 'db/db_config.php';

$info = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $action = $_POST["action"]; // "login" or "register"

    if ($action === "register") {
        $username = trim($_POST["username"]);
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed);

        if ($stmt->execute()) {
            $info = "Registered successfully! Please login.";
        } else {
            $info = "Registration failed: " . $conn->error;
        }

    } elseif ($action === "login") {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                header("Location: dashboard.php");
                exit();
            } else {
                $info = "Wrong password.";
            }
        } else {
            $info = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Drive - Login/Register</title>
</head>
    <style>
            body {
                background-color: #f8f9fa;
                font-family: Arial, sans-serif;
            }

            .container {
                max-width: 400px;
                margin: 80px auto;
                padding: 40px;
                background: white;
                border-radius: 30px;
                box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.1);
            }

            input[type="text"],
            input[type="email"],
            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border-radius: 10px;
                border: 1px solid #ccc;
            }

            button {
                margin-left: 60%;
                padding: 10px;
                background: #007bff;
                border: none;
                color: white;
                width: 40%;
                border-radius: 5px;
                margin-top: 10px;
            }        
    </style>
<body>

    <div class="container">

        <h2>CloudDrive</h2>
        <p style="color:red;">
            <?php echo $info; ?>
        </p>

        <form method="POST">
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <hr>

        <form method="POST">
            <input type="hidden" name="action" value="register">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>


    </div>
</body>

</html>