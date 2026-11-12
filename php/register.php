
<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Please submit the donor registration form.");
}

$name   = trim($_POST['name'] ?? '');
$age    = trim($_POST['age'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$blood  = trim($_POST['blood'] ?? '');
$phone  = trim($_POST['phone'] ?? '');
$city   = trim($_POST['city'] ?? '');

if (
    $name === '' ||
    $age === '' ||
    $gender === '' ||
    $blood === '' ||
    $phone === '' ||
    $city === ''
) {
    die("Please fill in all the required fields.");
}

if (!filter_var($age, FILTER_VALIDATE_INT) || (int)$age <= 0) {
    die("Please enter a valid age.");
}

$age = (int)$age;

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO donors (name, age, gender, blood_group, phone, city)
     VALUES (?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    die("Registration failed. Please try again.");
}

mysqli_stmt_bind_param(
    $stmt,
    "sissss",
    $name,
    $age,
    $gender,
    $blood,
    $phone,
    $city
);

if (mysqli_stmt_execute($stmt)) {
    echo "Registration successful!";
} else {
    echo "Registration failed. Please try again.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>
