<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="container">
        <h2>Welcome!</h2>
        <?php
    session_start();
    $hostName = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "login_register";
    $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

    if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $username = $row["username"];
        $email = $row["email"];
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "SQL statement preparation failed.";
    exit();
}
$updateSuccess = false;

    if (isset($_POST["update-profile"])) {
    $newUsername = $_POST["new-username"];
    $newEmail = $_POST["new-email"];
    $newPassword = $_POST["new-password"];

    $updateQuery = "UPDATE users SET username=?, email=?, password=? WHERE id=?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $updateQuery)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "sssi", $newUsername, $newEmail, $hashedPassword, $user_id);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        $updateSuccess = true;
        $_SESSION["update_success"] = true;
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
    } else {
        echo "Update query preparation failed.";
        exit();
    }
}
?>
        <form id="updateProfileForm" action="profile.php" method="post">
        <div class="form-group">
                <label for="new-username">New Username:</label>
                <input type="text" id="new-username" name="new-username"  required>
            </div>
            <div class="form-group">
                <label for="new-email">New Email:</label>
                <input type="email" id="new-email" name="new-email"  required>
            </div>
            <div class="form-group">
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Update Profile" name="update-profile">
            </div>
        </form>
        <p><a href="login.php">Logout</a></p>
    </div>
</body>

</html>

