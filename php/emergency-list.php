
<?php

include 'db.php';

$sql = "SELECT * FROM emergency_requests
        WHERE status = 'Pending'
        ORDER BY request_time DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Requests | LifeLink</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ffeaea;
            padding: 30px;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #b30000;
        }

        .request-card {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            box-sizing: border-box;
        }

        .request-card h2 {
            color: #b30000;
        }

        .request-card p {
            font-size: 17px;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
        }

        .action-buttons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 18px;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .action-buttons .accept-btn {
            background: #0d9488;
        }

        .action-buttons .reject-btn {
            background: #64748b;
        }

        .action-buttons .delete-btn {
            background: #dc2626;
        }

        .call-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 18px;
            margin: 5px 5px 5px 0;
            border-radius: 10px;
            background: #b30000;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: 0.2s ease;
        }

        .call-btn:hover {
            background: #8f0000;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

<h1>🚨 Emergency Blood Requests</h1>

<?php if ($result && mysqli_num_rows($result) > 0): ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>

        <?php
        $blood_group = htmlspecialchars($row['blood_group'] ?? '', ENT_QUOTES, 'UTF-8');
        $patient_name = htmlspecialchars($row['patient_name'] ?? '', ENT_QUOTES, 'UTF-8');
        $hospital = htmlspecialchars($row['hospital'] ?? '', ENT_QUOTES, 'UTF-8');
        $city = htmlspecialchars($row['city'] ?? '', ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($row['message'] ?? '', ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($row['phone'] ?? '', ENT_QUOTES, 'UTF-8');
        $id = (int) $row['id'];
        ?>

        <div class="request-card">

            <h2>
                Blood Required: <?php echo $blood_group; ?>
            </h2>

            <p>
                <b>Patient Name:</b>
                <?php echo $patient_name; ?>
            </p>

            <p>
                <b>Hospital:</b>
                <?php echo $hospital; ?>
            </p>

            <p>
                <b>City:</b>
                <?php echo $city; ?>
            </p>

            <p>
                <b>Message:</b>
                <?php echo $message; ?>
            </p>

            <p>
                <b>Status:</b>
                <?php echo $status; ?>
            </p>

            <a class="call-btn" href="tel:<?php echo $phone; ?>">
                Call Hospital
            </a>

            <br><br>

            <a class="call-btn"
               href="../admin/find-donor.php?blood_group=<?php echo urlencode($row['blood_group'] ?? ''); ?>&city=<?php echo urlencode($row['city'] ?? ''); ?>">
                Find Donor
            </a>

            <br><br>

            <div class="action-buttons">

                <a class="accept-btn"
                   href="update-status.php?id=<?php echo $id; ?>&status=Accepted">
                    ✓ Accept
                </a>

                <a class="reject-btn"
                   href="update-status.php?id=<?php echo $id; ?>&status=Rejected">
                    ✕ Reject
                </a>

                <a class="delete-btn"
                   href="delete-emergency.php?id=<?php echo $id; ?>"
                   onclick="return confirm('Are you sure you want to delete this request?')">
                    🗑 Delete Request
                </a>

            </div>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <h2 style="text-align:center">
        No Emergency Requests Found
    </h2>

<?php endif; ?>

</body>
</html>

<?php
mysqli_close($conn);
?>
