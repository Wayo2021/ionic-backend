<?php
header("Access-Control-Allow-Origin: *");
include "config.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$message = array();

$years = $data['years'];
$studentOne = $data['studentOne'];
$studentTwo = $data['studentTwo'];
$id = $_GET['id'];

$q = mysqli_query($con, "UPDATE `students` SET `years` = '$years', `studentOne` = '$studentOne' , `studentTwo` = '$studentTwo' WHERE `id` = '{$id}' LIMIT 1");

if ($q) {
    // http_response_code(201);
    $message['status'] = "Success";
} else {
    http_response_code(422);
    $message['status'] = "Error";
}
echo json_encode($message);
echo mysqli_error($con);
