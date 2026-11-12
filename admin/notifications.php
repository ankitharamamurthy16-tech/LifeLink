
<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . '/../php/db.php';

$sql = "SELECT * FROM emergency_requests
        WHERE status = 'Pending'
        ORDER BY request_time DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Unable to load notifications.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Notifications | LifeLink</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #ffeaea;
    padding: 30px;
}

h1 {
    text-align: center;
    color: #b30000;
}

.notification {
    background: white;
    width: 80%;
    max-width: 700px;
    margin: 20px auto;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    box-sizing: border-box;
}

@media (max-width: 600px) {
    body {
        padding: 15px;
    }

    .notification {
        width: 100%;
    }
}

</style>

</head>

<body>

<h1>🔔 Blood Request Notifications</h1>

<?php if (mysqli_num_rows($result) > 0): ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>

        <div class="notification">

            <h2>
                🩸 <?php echo htmlspecialchars($row['blood_group'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                Required
            </h2>

            <p>
                <b>Patient:</b>
                <?php echo htmlspecialchars($row['patient_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <p>
                <b>City:</b>
                <?php echo htmlspecialchars($row['city'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <p>
                <b>Hospital:</b>
                <?php echo htmlspecialchars($row['hospital'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </p>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <h2 style="text-align:center;">No New Requests</h2>

<?php endif; ?>

</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>
