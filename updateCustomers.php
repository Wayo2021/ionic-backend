<?php

header("Access-Control-Allow-Origin: *");

$conn = mysqli_connect("localhost", "root", "", "app_drink_reward");
mysqli_set_charset($conn, "utf8");

$rawData = file_get_contents("php://input");
$postData = json_decode($rawData, true);
$message = array();

$cus_id = isset($postData['cus_id']) ? $postData['cus_id'] : '';
$cus_first_name = isset($postData['cus_first_name']) ? $postData['cus_first_name'] : '';
$cus_last_name = isset($postData['cus_last_name']) ? $postData['cus_last_name'] : '';
$cus_email = isset($postData['cus_email']) ? $postData['cus_email'] : '';
$cus_password = isset($postData['cus_password']) ? $postData['cus_password'] : '';
$cus_phone = isset($postData['cus_phone']) ? $postData['cus_phone'] : '';
$tot_id = isset($postData['tot_id']) ? $postData['tot_id'] : '';
$image = isset($postData['cus_email']) ? $postData['cus_email'] : '';
$poi_id = isset($postData['cus_password']) ? $postData['cus_password'] : '';
$cus_status = isset($postData['cus_status']) ? $postData['cus_status'] : '';
$cus_sex = isset($postData['cus_sex']) ? $postData['cus_sex'] : '';


$sql = "UPDATE customers SET cus_first_name = '" . $cus_first_name . "', cus_last_name = '" . $cus_last_name . "', cus_email = '" . $cus_email . "' WHERE cus_id = '" . $cus_id . "'";

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        $res = [
            'status' => 'success',
            'message' => ''
        ];
        printResponse($res);
        return;
    }


$conn->close();
$res = [
    'status' => 'error',
    'message' => $conn->error
];
printResponse($res);
return;

function printResponse($response)
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($response);
}
