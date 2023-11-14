<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <script src="login.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <?php
        session_start(); // Ensure session is started

        $hostName = "localhost";
        $dbUser = "root";
        $dbPassword = "";
        $dbName = "login_register";
        $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $loginError = "";
        $successMessage = "";

        if (isset($_POST["login"])) {
            $usernameOrEmail = $_POST["username_or_email"];
            $password = $_POST["password"];

            $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $usernameOrEmail, $usernameOrEmail);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    if (password_verify($password, $row["password"])) {
                        $_SESSION["user_id"] = $row["id"];
                        $successMessage = "Login successful. Redirecting to profile...";
                        header("refresh:0;url=profile.php");
                        exit();
                    } else {
                        $loginError = "Invalid password";
                    }
                } else {
                    $loginError = "Username/Email not found";
                }
            } else {
                $loginError = "Database error: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($conn);
        ?>

        <form id="loginForm" action="login.php" method="post">
            <div class="form-group">
                <label for="username_or_email">Username/Email:</label>
                <input type="text" id="username_or_email" name="username_or_email" required>
                <div class="error-message" id="username-email-error"></div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <div class="error-message" id="password-error"></div>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Login" name="login">
                <div class="error-message" id="login-error"><?php echo $loginError; ?></div>
                <p><a href="profile.php"></a></p>
            </div>
        </form>
        <p>Don't have an account? <a href="registration.php">Register here</a>.</p>
    </div>
</body>

</html>
