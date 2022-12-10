<?php
session_start();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// include "config.php";

// $data = file_get_contents("php://input");
// if (isset($data)) {
//     $request = json_decode($data);
//     $cus_phone = $request->cus_phone;
//     $cus_password = $request->cus_password;
// }
// $sql = "SELECT * FROM customers WHERE cus_phone = '$cus_phone' and cus_password = '$cus_password' ";
// $result = mysqli_query($con, $sql);
// $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

// $count = mysqli_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row

// if ($count > 0) {
//     $response = "Your Login success";
// } else {
//     $response->error;
//     $response = "Your Login Email or Password is invalid";
// }

// echo json_encode($response);

// $member = [];


// if ($result->num_rows == 0) {
//     $resp = [
//         'status' => 'fail',
//         'message' => 'data not found',
//         'data' => null
//     ];
//     printResponse($resp);
//     return;
// }

// $member = $result->fetch_assoc();
// $con->close();

// $resp = [
//     'status' => 'success',
//     'message' => '',
//     'data' => [
//         'member' => $member
//     ]
// ];
// printResponse($resp);

// function printResponse($response)
// {
//     header('Content-type: application/json; charset=utf-8');
//     echo json_encode($response);
// }


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

$conn = mysqli_connect("localhost", "root", "", "app_drink_reward");
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    $res = [
        'status' => 'error',
        'message' => 'can not connect database'
    ];
    echo json_encode($res);
    exit;
}

$rawData = file_get_contents("php://input");
$postData = json_decode($rawData, true);


$cus_phone = isset($postData['cus_phone']) ? $postData['cus_phone'] : '';
$cus_password = isset($postData['cus_password']) ? $postData['cus_password'] : '';


$sql = "
SELECT 
*
FROM customers
WHERE
    cus_phone = '" . $cus_phone . "'
    AND cus_password = '" . $cus_password . "'";


// $rs = mysqli_query($conn, $sql);
$rs = $conn->query($sql);

//?
$customers = [];

if ($rs->num_rows == 0) {
    
    $resp = [
        'status' => 'fail',
        'message' => 'data not found',
        'data' => null
    ];
    printResponse($resp);
    return;
}

$customers = $rs->fetch_assoc();
$conn->close();

$resp = [
    'status' => 'success',
    'message' => '',
    'data' => [
        'customers' => $customers
    ]
];
printResponse($resp);

function printResponse($response)
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($response);
}