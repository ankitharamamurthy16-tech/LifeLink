
<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . '/../php/db.php';

$search = trim($_GET['search'] ?? '');
$blood_group = trim($_GET['blood_group'] ?? '');
$city = trim($_GET['city'] ?? '');

$query = "SELECT * FROM donors WHERE 1=1";

$params = [];
$types = "";

if ($search !== '') {
    $query .= " AND (name LIKE ? OR phone LIKE ? OR city LIKE ?)";

    $search_value = "%" . $search . "%";

    $params[] = $search_value;
    $params[] = $search_value;
    $params[] = $search_value;

    $types .= "sss";
}

if ($blood_group !== '') {
    $query .= " AND blood_group = ?";
    $params[] = $blood_group;
    $types .= "s";
}

if ($city !== '') {
    $query .= " AND city LIKE ?";
    $params[] = "%" . $city . "%";
    $types .= "s";
}

$query .= " ORDER BY name ASC";

$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    die("Unable to load donor records.");
}

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Unable to retrieve donor records.");
}

$total_donors = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>All Donors | LifeLink</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
}

header {
    background: #b30000;
    color: white;
    padding: 20px;
    text-align: center;
}

table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    background: white;
}

th {
    background: #b30000;
    color: white;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}

.call-btn {
    background: #28a745;
    color: white;
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
}

.call-btn:hover {
    opacity: 0.8;
}

.edit-btn {
    background: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    margin-right: 5px;
}

.delete-btn {
    background: #dc3545;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
}

</style>

<link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<header>

    <h1>🩸 Registered Blood Donors</h1>

    <div class="donor-count">
        Total Registered Donors:
        <?php echo $total_donors; ?>
    </div>

</header>

<form method="GET">

    <input
        type="text"
        name="search"
        placeholder="Search name, phone or city"
        value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
    >

    <select name="blood_group">

        <option value="">All Blood Groups</option>

        <?php
        $groups = ["A+","A-","B+","B-","O+","O-","AB+","AB-"];

        foreach ($groups as $group):
        ?>

            <option
                value="<?php echo $group; ?>"
                <?php echo ($blood_group === $group) ? 'selected' : ''; ?>
            >
                <?php echo $group; ?>
            </option>

        <?php endforeach; ?>

    </select>

    <button type="submit">Search</button>

</form>

<div class="table-wrapper">

<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Age</th>
    <th>Gender</th>
    <th>Blood Group</th>
    <th>Phone</th>
    <th>City</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)): ?>

<?php
$id = (int)$row['id'];

$name = htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8');
$age = (int)($row['age'] ?? 0);
$gender = htmlspecialchars($row['gender'] ?? '', ENT_QUOTES, 'UTF-8');
$blood = htmlspecialchars($row['blood_group'] ?? '', ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($row['phone'] ?? '', ENT_QUOTES, 'UTF-8');
$city_name = htmlspecialchars($row['city'] ?? '', ENT_QUOTES, 'UTF-8');
$status = htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8');
$status_class = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', $row['status'] ?? ''));
?>

<tr>

    <td><?php echo $id; ?></td>

    <td><?php echo $name; ?></td>

    <td><?php echo $age; ?></td>

    <td><?php echo $gender; ?></td>

    <td>
        <span class="blood-badge">
            <?php echo $blood; ?>
        </span>
    </td>

    <td>
        <?php echo $phone; ?>

        <br>

        <a class="call-btn"
           href="tel:<?php echo $phone; ?>">
            📞 Call
        </a>
    </td>

    <td><?php echo $city_name; ?></td>

    <td>
        <span class="status-badge <?php echo $status_class; ?>">
            <?php echo $status; ?>
        </span>
    </td>

    <td>

        <a class="edit-btn"
           href="edit-donor.php?id=<?php echo $id; ?>">
            ✏️ Edit
        </a>

        <a class="delete-btn"
           href="delete-donor.php?id=<?php echo $id; ?>"
           onclick="return confirm('Delete this donor?');">
            🗑️ Delete
        </a>

    </td>

</tr>

<?php endwhile; ?>

<?php if ($total_donors === 0): ?>

<tr>
    <td colspan="9">
        No donors found.
    </td>
</tr>

<?php endif; ?>

</table>

</div>

</body>
</html>

<?php

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>
