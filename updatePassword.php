<?php

header("Access-Control-Allow-Origin: *");

$conn = mysqli_connect("localhost", "root", "", "app_drink_reward");
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    $res = [
        'status' => 'error',
        'message' => 'can not connect database'
    ];
    printResponse($res);
    return;
}

$rawData = file_get_contents("php://input");
$postData = json_decode($rawData, true);

$cus_id = isset($postData['cus_id']) ? $postData['cus_id'] : '';
$cus_password = isset($postData['cus_password']) ? $postData['cus_password'] : '';
$emailPhone = isset($postData['emailPhone']) ? $postData['emailPhone'] : '';




$sql = "
UPDATE `customers` SET `cus_password`='" . $cus_password . "' WHERE `cus_id`='" . $cus_id . "'
";

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
