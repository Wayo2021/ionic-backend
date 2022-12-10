<?php
include "config.php";
$data = array();

$q = mysqli_query($con, "SELECT * FROM `size`");
$numrow = mysqli_num_rows($q);

if ($numrow > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($q)) {
        $data[] = $row;
    }
    echo json_encode($data);
    mysqli_close($con);
}else {
    echo json_encode(null);
    echo mysqli_error($con);
}


