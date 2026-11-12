
<?php

include 'db.php';

if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = (int) $_GET['id'];
    $status = trim($_GET['status']);

    if ($id > 0 && $status !== '') {

        // Update donor status safely
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE donors SET status = ? WHERE id = ?"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $status, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Get donor's blood group and city
        $stmt = mysqli_prepare(
            $conn,
            "SELECT blood_group, city FROM donors WHERE id = ?"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if ($row) {
                $blood_group = urlencode($row['blood_group']);
                $city = urlencode($row['city']);

                mysqli_close($conn);

                header(
                    "Location: ../admin/find-donor.php?blood_group=$blood_group&city=$city"
                );
                exit();
            }
        }
    }
}

mysqli_close($conn);

header("Location: ../admin/find-donor.php");
exit();

?>
