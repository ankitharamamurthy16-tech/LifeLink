
<?php

include 'db.php';

$groups = ["A+","A-","B+","B-","AB+","AB-","O+","O-"];

$count = [];

$stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total FROM donors WHERE blood_group = ?"
);

if ($stmt) {

    foreach ($groups as $group) {

        mysqli_stmt_bind_param($stmt, "s", $group);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $count[$group] = $row['total'];
        } else {
            $count[$group] = 0;
        }
    }

    mysqli_stmt_close($stmt);

} else {

    foreach ($groups as $group) {
        $count[$group] = 0;
    }

}

?>
