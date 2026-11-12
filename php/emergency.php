
<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Please submit the emergency request form.");
}

$patient_name = trim($_POST['patient_name'] ?? '');
$blood_group  = trim($_POST['blood_group'] ?? '');
$hospital     = trim($_POST['hospital'] ?? '');
$phone        = trim($_POST['phone'] ?? '');
$city         = trim($_POST['city'] ?? '');
$message      = trim($_POST['message'] ?? '');

if (
    $patient_name === '' ||
    $blood_group === '' ||
    $hospital === '' ||
    $phone === '' ||
    $city === '' ||
    $message === ''
) {
    die("Some fields are missing. Please return to the form and fill in every field.");
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO emergency_requests
    (patient_name, blood_group, hospital, phone, city, message)
    VALUES (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $patient_name,
    $blood_group,
    $hospital,
    $phone,
    $city,
    $message
);

if (mysqli_stmt_execute($stmt)) {
    echo "Emergency request submitted successfully!";
} else {
    echo "Error saving request. Please try again.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>
