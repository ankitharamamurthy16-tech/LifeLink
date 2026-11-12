<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
?>
<?php
include("php/blood-count.php");
include("php/dashboard-count.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeLink - Blood Donor Finder</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<!-- Navigation -->

<nav>

    <div class="logo">
        <i class="fa-solid fa-droplet"></i> LifeLink
    </div>

    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="register.html">Donate Blood</a></li>
        <li><a href="search.html">Find Blood</a></li>
        <li><a href="emergency-request.html">Emergency</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.html">Contact</a></li>
    </ul>

</nav>


<!-- Hero Section -->

<section class="hero">

    <div class="left">

        <h1>Every Drop <span>Counts</span></h1>

        <h2>Connecting Donors. Saving Lives.</h2>

        <p>
            LifeLink helps people quickly find verified blood donors nearby during emergencies.
        </p>

        <div class="buttons">

            <a href="register.html" class="btn">Become a Donor</a>

            <a href="search.html" class="btn2">Find Blood</a>

            <a href="emergency-request.html" class="btn3">Emergency Request</a>

        </div>

    </div>

    <div class="right">

        <div class="blood-drop">
            <i class="fa-solid fa-droplet"></i>
        </div>

    </div>

</section>

<section class="stats">

    <div class="stat-box">
        <i class="fa-solid fa-users"></i>
        <h2><?php echo $total_donors; ?></h2>
        <p>Total Donors</p>
    </div>

    <div class="stat-box">
        <i class="fa-solid fa-hand-holding-droplet"></i>
        <h2><?php echo $available_groups; ?></h2>
        <p>Blood Groups</p>
    </div>

    <div class="stat-box">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <h2><?php echo $emergency_requests; ?></h2>
        <p>Emergency Requests</p>
    </div>

</section>


<!-- Quick Actions -->
<section class="features">

    <h2>Quick Actions</h2>

    <div class="feature-container">

        <!-- Register -->
        <a href="register.html" class="card action-card">
            <i class="fa-solid fa-user-plus"></i>

            <h3>Become a Donor</h3>

            <p>
                Register yourself as a blood donor and help save lives during emergencies.
            </p>

            <span class="action-btn">
                Register Now →
            </span>
        </a>

        <!-- Search -->
        <a href="search.html" class="card action-card">
            <i class="fa-solid fa-magnifying-glass"></i>

            <h3>Find Blood</h3>

            <p>
                Search verified blood donors by blood group, city and area instantly.
            </p>

            <span class="action-btn">
                Search Donors →
            </span>
        </a>

        <!-- Emergency -->
        <a href="emergency-request.html" class="card action-card">
            <i class="fa-solid fa-phone-volume"></i>

            <h3>Emergency Request</h3>

            <p>
                Post an urgent blood request so nearby donors can respond immediately.
            </p>

            <span class="action-btn">
                Request Blood →
            </span>
        </a>

    </div>

</section>

<!-- Blood Availability -->

<section class="availability">

    <h2>Blood Availability</h2>

    <div class="blood-grid">

        <?php
        $groups = ["A+","A-","B+","B-","AB+","AB-","O+","O-"];

        foreach($groups as $group)
        {
            $result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM donors WHERE blood_group='$group'");
            $row = mysqli_fetch_assoc($result);
        ?>

        <div class="blood-card">

    <h3><?php echo $group; ?></h3>

    <span><?php echo $row['total']; ?> Donors</span>

        </div>

        <?php
        }
        ?>

    </div>

</section>


<!-- Features -->

<section class="features">

    <h2>Why Choose LifeLink?</h2>

    <div class="feature-container">

        <div class="card">
            <i class="fa-solid fa-user-check"></i>
            <h3>Verified Donors</h3>
            <p>Only hospital-approved donors are displayed.</p>
        </div>

        <div class="card">
            <i class="fa-solid fa-location-dot"></i>
            <h3>Nearby Search</h3>
            <p>Find blood donors in your own area quickly.</p>
        </div>

        <div class="card">
            <i class="fa-solid fa-hospital"></i>
            <h3>Hospital Support</h3>
            <p>Hospitals can verify donors and respond to emergencies.</p>
        </div>

        <div class="card">
            <i class="fa-solid fa-bolt"></i>
            <h3>Emergency Alerts</h3>
            <p>Post urgent blood requests instantly.</p>
        </div>

    </div>

</section>

<!-- Footer -->

<footer>

    <div class="footer-container">

        <div class="footer-box">
            <h2><i class="fa-solid fa-droplet"></i> LifeLink</h2>
            <p>
                Connecting donors and patients during emergencies.
                Every drop can save a life.
            </p>
        </div>


        <div class="footer-box">
            <h3>Quick Links</h3>
            <a href="index.php">Home</a>
            <a href="register.html">Donate Blood</a>
            <a href="search.html">Find Blood</a>
            <a href="emergency-request.html">Emergency</a>
        </div>


        <div class="footer-box">
            <h3>Contact</h3>
            <p><i class="fa-solid fa-envelope"></i> lifelink@gmail.com</p>
            <p><i class="fa-solid fa-phone"></i> +91 XXXXX XXXXX</p>
        </div>

    </div>


    <div class="footer-bottom">
        © 2026 LifeLink | Every Drop Counts
    </div>

</footer>

<script src="js/script.js"></script>

</body>
</html>