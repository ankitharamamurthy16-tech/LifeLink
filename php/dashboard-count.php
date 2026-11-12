<?php

include 'db.php';

// Total Donors
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM donors");

if ($result) {
    $total_donors = mysqli_fetch_assoc($result)['total'];
} else {
    $total_donors = 0;
}

// Blood Groups Available
$result = mysqli_query($conn, "SELECT COUNT(DISTINCT blood_group) AS total FROM donors");

if ($result) {
    $available_groups = mysqli_fetch_assoc($result)['total'];
} else {
    $available_groups = 0;
}

// Emergency Requests
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM emergency_requests");

if ($result) {
    $emergency_requests = mysqli_fetch_assoc($result)['total'];
} else {
    $emergency_requests = 0;
}

?>