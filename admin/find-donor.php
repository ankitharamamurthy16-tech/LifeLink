
<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . '/../php/db.php';

$groups = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];

$selected_blood = trim($_GET['blood_group'] ?? $_POST['blood_group'] ?? '');
$selected_city = trim($_GET['city'] ?? $_POST['city'] ?? '');

$result = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['blood_group'])) {

    if (
        $selected_blood === '' ||
        !in_array($selected_blood, $groups, true)
    ) {
        $error = "Please select a valid blood group.";
    } else {

        $sql = "SELECT * FROM donors
                WHERE blood_group = ?
                AND city LIKE ?
                ORDER BY name ASC";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            $city_search = "%" . $selected_city . "%";

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $selected_blood,
                $city_search
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if (!$result) {
                $error = "Unable to retrieve donor records.";
            }

        } else {
            $error = "Unable to search for donors.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Find Matching Donor | LifeLink</title>

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

form {
    background: white;
    width: 400px;
    max-width: 100%;
    margin: 20px auto;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    box-sizing: border-box;
}

input, select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 8px;
    box-sizing: border-box;
}

button {
    background: #b30000;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}

.donor-card {
    background: white;
    width: 350px;
    max-width: 100%;
    margin: 20px auto;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    box-sizing: border-box;
}

.call-btn {
    display: inline-block;
    background: #b30000;
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    text-decoration: none;
}

.delete-btn {
    display: inline-block;
    background: #d9534f;
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    text-decoration: none;
}

.delete-btn:hover {
    background: #a71d2a;
}

.error-message {
    text-align: center;
    color: #b30000;
}

</style>

</head>

<body>

<h1>🩸 Find Blood Donor</h1>

<form method="POST">

    Blood Group:

    <select name="blood_group" required>

        <option value="">Select Blood Group</option>

        <?php foreach ($groups as $group): ?>

            <option
                value="<?php echo htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo ($selected_blood === $group) ? 'selected' : ''; ?>
            >
                <?php echo htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    City:

    <input
        type="text"
        name="city"
        placeholder="Enter city"
        value="<?php echo htmlspecialchars($selected_city, ENT_QUOTES, 'UTF-8'); ?>"
    >

    <br><br>

    <button type="submit" name="search">
        Search Donor
    </button>

</form>

<?php if ($error !== ''): ?>

    <h3 class="error-message">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
    </h3>

<?php elseif ($result !== false): ?>

    <?php if (mysqli_num_rows($result) > 0): ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>

            <?php
            $id = (int)$row['id'];
            $name = htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8');
            $blood = htmlspecialchars($row['blood_group'] ?? '', ENT_QUOTES, 'UTF-8');
            $city = htmlspecialchars($row['city'] ?? '', ENT_QUOTES, 'UTF-8');
            $phone = htmlspecialchars($row['phone'] ?? '', ENT_QUOTES, 'UTF-8');
            $status = htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8');
            ?>

            <div class="donor-card">

                <h3>🩸 Donor Found</h3>

                <p>
                    <b>Name:</b>
                    <?php echo $name; ?>
                </p>

                <p>
                    <b>Blood Group:</b>
                    <?php echo $blood; ?>
                </p>

                <p>
                    <b>City:</b>
                    <?php echo $city; ?>
                </p>

                <p>
                    <b>Phone:</b>
                    <?php echo $phone; ?>
                </p>

                <p>
                    <b>Status:</b>

                    <?php if ($status === 'Available'): ?>

                        <span style="color:green;font-weight:bold;">
                            🟢 Available
                        </span>

                    <?php else: ?>

                        <span style="color:red;font-weight:bold;">
                            🔴 Unavailable
                        </span>

                    <?php endif; ?>

                </p>

                <a class="call-btn"
                   href="tel:<?php echo $phone; ?>">
                    📞 Call Donor
                </a>

                <br><br>

                <a class="call-btn"
                   href="../php/update-donor-status.php?id=<?php echo $id; ?>&status=Available">
                    🟢 Available
                </a>

                &nbsp;

                <a class="delete-btn"
                   href="../php/update-donor-status.php?id=<?php echo $id; ?>&status=Unavailable">
                    🔴 Unavailable
                </a>

                <hr>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <h3 class="error-message">
            No Matching Donor Found
        </h3>

    <?php endif; ?>

<?php endif; ?>

</body>
</html>

<?php

if (isset($stmt) && $stmt) {
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>
