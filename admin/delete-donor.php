
<?php

require_once __DIR__ . '/../php/db.php';

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    if ($id <= 0) {
        die("Invalid donor ID.");
    }

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM donors WHERE id = ?"
    );

    if (!$stmt) {
        die("Unable to process the request.");
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: donor.php");
        exit();

    } else {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        die("Unable to delete donor. Please try again.");

    }

} else {

    mysqli_close($conn);
    die("Invalid request.");

}

?>
