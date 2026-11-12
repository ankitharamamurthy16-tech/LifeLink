<?php

include 'db.php';

if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = (int) $_GET['id'];
    $status = $_GET['status'];

    if (in_array($status, ['Accepted', 'Rejected'])) {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE emergency_requests SET status = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

header("Location: emergency-list.php");
exit();

?>