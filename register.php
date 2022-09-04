<?php
include_once 'config.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$message = array();

$firstname = $data['firstname'];
$lastname = $data['lastname'];
$username = $data['username'];
$password = $data['password'];

$sql = mysqli_query($con, "INSERT INTO users (firstname, lastname, username, password) VALUES ('$firstname', '$lastname', '$username', '$password')");
if ($sql) {
    http_response_code(201);
    $message['status'] = "Success";
} else {
    http_response_code(422);
    $message['status'] = "Error";
}
echo json_encode($message);
echo mysqli_error($con);
