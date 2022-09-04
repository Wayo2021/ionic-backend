<?php
include "config.php";
$input = file_get_contents('php://input');
$data = json_decode($input, true);
$message = array();

$years = $data['years'];
$studentOne = $data['studentOne'];
$studentTwo = $data['studentTwo'];

$q = mysqli_query($con, "INSERT INTO `students`(`years`,`studentOne`,`studentTwo`) VALUES ('$years','$studentOne','$studentTwo')");

if ($q) {
    http_response_code(201);
    $message['status'] = "Success";
} else {
    http_response_code(422);
    $message['status'] = "Error";
}
echo json_encode($message);
echo mysqli_error($con);
