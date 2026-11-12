
<?php
include 'db.php';

$blood_group = $_GET['blood_group'] ?? '';
$city = $_GET['city'] ?? '';

$sql = "SELECT * FROM donors WHERE 1=1";
$params = [];
$types = "";

if ($blood_group !== '') {
    $sql .= " AND blood_group = ?";
    $params[] = $blood_group;
    $types .= "s";
}

if ($city !== '') {
    $sql .= " AND city LIKE ?";
    $params[] = "%" . $city . "%";
    $types .= "s";
}

$sql .= " ORDER BY name ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Search failed. Please try again.");
}

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Blood Donors | LifeLink</title>
    <link rel="stylesheet" href="../css/search.css">
</head>
<body>

<h1 class="results-title">Available Blood Donors</h1>

<div class="donor-container">

<?php if (mysqli_num_rows($result) > 0): ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>

        <div class="donor-card">
            <h2>
                <?php echo htmlspecialchars($row['name'] ?? ''); ?>
            </h2>

            <p>
                <b>Blood Group:</b>
                <?php echo htmlspecialchars($row['blood_group'] ?? ''); ?>
            </p>

            <p>
                <b>City:</b>
                <?php echo htmlspecialchars($row['city'] ?? ''); ?>
            </p>

            <p>
                <b>Phone:</b>
                <?php echo htmlspecialchars($row['phone'] ?? ''); ?>
            </p>

            <a class="call-btn"
               href="tel:<?php echo htmlspecialchars($row['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                📞 Call Donor
            </a>
        </div>

    <?php endwhile; ?>

<?php else: ?>

    <p class="no-results">
        No donors found. Try another blood group or city.
    </p>

<?php endif; ?>

</div>

</body>
</html>
<?php
mysqli_stmt_close($stmt);
?>
