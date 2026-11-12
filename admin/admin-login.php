
<?php

session_start();

// Get login form data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Admin login check
if ($username === "admin" && $password === "admin123") {

    session_regenerate_id(true);

    $_SESSION['admin'] = $username;

    header("Location: dashboard.php");
    exit();

} else {

    echo "Invalid username or password.";

}

?>
