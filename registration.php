<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple User Registration System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Registration Page</h1>
        <?php
    $hostName = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "login_register";
    $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
    $errors = array();  
    $successMessage = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);
    if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
        array_push($errors, "All fields are required");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Password doesn't match");
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        array_push($errors, "Password must contain at least one special character");
    }
    $checkUsernameQuery = "SELECT * FROM users WHERE username=?";
    $checkUsernameStmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($checkUsernameStmt, $checkUsernameQuery)) {
        mysqli_stmt_bind_param($checkUsernameStmt, "s", $username);
        mysqli_stmt_execute($checkUsernameStmt);
        $result = mysqli_stmt_get_result($checkUsernameStmt);

        if (mysqli_fetch_assoc($result)) {
            array_push($errors, "Username already exists");
        }
    }
    $checkEmailQuery = "SELECT * FROM users WHERE email=?";
    $checkEmailStmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($checkEmailStmt, $checkEmailQuery)) {
        mysqli_stmt_bind_param($checkEmailStmt, "s", $email);
        mysqli_stmt_execute($checkEmailStmt);
        $result = mysqli_stmt_get_result($checkEmailStmt);

        if (mysqli_fetch_assoc($result)) {
            array_push($errors, "Email already exists");
        }
    }
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $insertStmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($insertStmt, $insertQuery)) {
            mysqli_stmt_bind_param($insertStmt, "sss", $username, $email, $passwordhash);
            mysqli_stmt_execute($insertStmt);
            echo "<div class='alert alert-success'>You are registered successfully.</div>";
        } else {
            die("Something went wrong");
        }
    }
    if (isset($checkUsernameStmt)) {
        mysqli_stmt_close($checkUsernameStmt);
    }

    if (isset($checkEmailStmt)) {
        mysqli_stmt_close($checkEmailStmt);
    }
    if (isset($insertStmt)) {
        mysqli_stmt_close($insertStmt);
    }
}
mysqli_close($conn);
?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="repeat_password">Repeat Password:</label>
                <input type="password" id="repeat_password" name="repeat_password" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>

        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>

</html>

