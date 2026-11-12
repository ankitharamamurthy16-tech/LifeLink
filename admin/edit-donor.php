
<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . '/../php/db.php';

$row = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $id = (int)($_POST['id'] ?? 0);

    $name = trim($_POST['name'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $blood_group = trim($_POST['blood_group'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $area = trim($_POST['area'] ?? '');
    $last_donation = trim($_POST['last_donation'] ?? '');

    if (
        $id <= 0 || $name === '' || $age === '' ||
        $gender === '' || $blood_group === '' ||
        $phone === '' || $city === ''
    ) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($age, FILTER_VALIDATE_INT) || (int)$age <= 0) {
        $error = "Please enter a valid age.";
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        $age = (int)$age;

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE donors SET
                name = ?,
                age = ?,
                gender = ?,
                blood_group = ?,
                phone = ?,
                email = ?,
                state = ?,
                city = ?,
                area = ?,
                last_donation = NULLIF(?, '')
             WHERE id = ?"
        );

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "sissssssssi",
                $name,
                $age,
                $gender,
                $blood_group,
                $phone,
                $email,
                $state,
                $city,
                $area,
                $last_donation,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);
                mysqli_close($conn);

                header("Location: donor.php");
                exit();

            } else {
                $error = "Update failed. Please try again.";
            }

            mysqli_stmt_close($stmt);

        } else {
            $error = "Unable to prepare the update.";
        }
    }
}

// Load donor details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int)($_GET['id'] ?? 0);
} 

if ($id > 0 && $row === null) {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM donors WHERE id = ?"
    );

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
    }
}

if (!$row && $error === '') {
    $error = "Donor not found.";
}

function safeValue($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Donor | LifeLink</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    padding: 25px;
}

form {
    max-width: 500px;
    margin: 20px auto;
    padding: 25px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

input {
    width: 100%;
    padding: 10px;
    margin: 8px 0 15px;
    box-sizing: border-box;
}

button {
    background: #b30000;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
}

.error {
    color: #b30000;
    text-align: center;
}

</style>

</head>

<body>

<h2 style="text-align:center;">Edit Donor Details</h2>

<?php if ($error !== ''): ?>

    <p class="error"><?php echo safeValue($error); ?></p>

<?php endif; ?>

<?php if ($row): ?>

<form method="POST">

<input type="hidden" name="id"
       value="<?php echo (int)$row['id']; ?>">

Name:
<input type="text" name="name" required
       value="<?php echo safeValue($row['name']); ?>">

Age:
<input type="number" name="age" required
       value="<?php echo safeValue($row['age']); ?>">

Gender:
<input type="text" name="gender" required
       value="<?php echo safeValue($row['gender']); ?>">

Blood Group:
<input type="text" name="blood_group" required
       value="<?php echo safeValue($row['blood_group']); ?>">

Phone:
<input type="text" name="phone" required
       value="<?php echo safeValue($row['phone']); ?>">

Email:
<input type="email" name="email"
       value="<?php echo safeValue($row['email']); ?>">

State:
<input type="text" name="state"
       value="<?php echo safeValue($row['state']); ?>">

City:
<input type="text" name="city" required
       value="<?php echo safeValue($row['city']); ?>">

Area:
<input type="text" name="area"
       value="<?php echo safeValue($row['area']); ?>">

Last Donation:
<input type="date" name="last_donation"
       value="<?php echo safeValue($row['last_donation']); ?>">

<button type="submit" name="update">Update Donor</button>

</form>

<?php endif; ?>

</body>
</html>

<?php
mysqli_close($conn);
?>
