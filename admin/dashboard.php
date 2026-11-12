
<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit;
}

require_once __DIR__ . '/../php/db.php';

function lifelinkCount($conn, $sql)
{
    $result = $conn->query($sql);

    if ($result && ($row = $result->fetch_assoc())) {
        return (int) $row['total'];
    }

    return 0;
}

$donorCount = lifelinkCount(
    $conn,
    "SELECT COUNT(*) AS total FROM donors"
);

$availableCount = lifelinkCount(
    $conn,
    "SELECT COUNT(*) AS total FROM donors WHERE status = 'Available'"
);

$unavailableCount = lifelinkCount(
    $conn,
    "SELECT COUNT(*) AS total FROM donors WHERE status = 'Unavailable'"
);

$pendingCount = lifelinkCount(
    $conn,
    "SELECT COUNT(*) AS total FROM emergency_requests WHERE status = 'Pending'"
);

$acceptedCount = lifelinkCount(
    $conn,
    "SELECT COUNT(*) AS total FROM emergency_requests WHERE status = 'Accepted'"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LifeLink | Admin Dashboard</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

<div class="dashboard-shell">

    <header class="dashboard-header">
        <div>
            <p class="eyebrow">LIFELINK ADMINISTRATION</p>
            <h1>Dashboard</h1>
            <p class="header-description">
                Manage blood donors and emergency requests in one place.
            </p>
        </div>

        <a href="logout.php" class="logout-button">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>
    </header>

    <main class="dashboard-content">

        <!-- DONOR AND REQUEST OVERVIEW -->
        <section class="top-layout">

            <!-- DONOR OVERVIEW OUTER BOX -->
            <div class="donor-panel">

                <div class="section-heading">
                    <div>
                        <h2>Donor Overview</h2>
                        <p>Current donor availability</p>
                    </div>

                    <span class="heading-icon">
                        <i class="fa-solid fa-droplet"></i>
                    </span>
                </div>

                <!-- TOTAL DONORS -->
                <div class="summary-card total-donors-card">

                    <div class="icon-bubble">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <h3>Total Donors</h3>

                    <p class="card-number">
                        <?php echo $donorCount; ?>
                    </p>

                    <a href="donor.php" class="card-button">
                        View Donors
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

                <!-- AVAILABLE AND UNAVAILABLE -->
                <div class="donor-bottom-row">

                    <div class="summary-card donor-status-card">

                        <div class="icon-bubble">
                            <i class="fa-solid fa-user-check"></i>
                        </div>

                        <h3>Available Donors</h3>

                        <p class="card-number">
                            <?php echo $availableCount; ?>
                        </p>

                    </div>

                    <div class="summary-card donor-status-card">

                        <div class="icon-bubble">
                            <i class="fa-solid fa-user-xmark"></i>
                        </div>

                        <h3>Unavailable Donors</h3>

                        <p class="card-number">
                            <?php echo $unavailableCount; ?>
                        </p>

                    </div>

                </div>

            </div>

            <!-- REQUEST OVERVIEW -->
            <div class="requests-panel">

                <div class="section-heading">
                    <div>
                        <h2>Request Overview</h2>
                        <p>Track emergency blood requests</p>
                    </div>

                    <span class="heading-icon">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </span>
                </div>

                <div class="requests-grid">

                    <!-- PENDING REQUESTS -->
                    <div class="summary-card request-card">

                        <div class="icon-bubble">
                            <i class="fa-solid fa-clock"></i>
                        </div>

                        <h3>Pending Requests</h3>

                        <p class="card-number">
                            <?php echo $pendingCount; ?>
                        </p>

                        <a href="../php/emergency-list.php"
                           class="card-button">
                            View Requests
                        </a>

                    </div>

                    <!-- ACCEPTED REQUESTS -->
                    <div class="summary-card request-card">

                        <div class="icon-bubble">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                        <h3>Accepted Requests</h3>

                        <p class="card-number">
                            <?php echo $acceptedCount; ?>
                        </p>

                        <a href="../php/emergency-list.php"
                           class="card-button">
                            View Status
                        </a>

                    </div>

                </div>

            </div>

        </section>

        <!-- QUICK ACTIONS -->
        <section class="tools-section">

            <div class="section-heading">
                <div>
                    <h2>Quick Actions</h2>
                    <p>Manage donors and incoming requests</p>
                </div>
            </div>

            <div class="tools-grid">

                <!-- FIND DONOR -->
                <div class="summary-card tool-card">

                    <div class="tool-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <div class="tool-content">
                        <h3>Find Donor</h3>
                        <p>
                            Search registered donors by blood group
                            and location.
                        </p>
                    </div>

                    <a href="find-donor.php" class="card-button">
                        Search Donors
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

                <!-- EMERGENCY NOTIFICATIONS -->
                <div class="summary-card tool-card">

                    <div class="tool-icon notification-icon">
                        <i class="fa-solid fa-bell"></i>
                    </div>

                    <div class="tool-content">
                        <h3>Emergency Notifications</h3>
                        <p>
                            Review new emergency blood requests
                            and update their status.
                        </p>

                        <p class="notification-count">
                            Pending requests:
                            <strong><?php echo $pendingCount; ?></strong>
                        </p>
                    </div>

                    <a href="../php/emergency-list.php"
                       class="card-button">
                        View Notifications
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>

        </section>

        <footer class="dashboard-footer">
            <p>LifeLink &mdash; Every Drop Can Save a Life.</p>
        </footer>

    </main>

</div>

</body>
</html>
