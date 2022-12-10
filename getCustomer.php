<?php


header("Access-Control-Allow-Origin: *"); //

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
// แปลง
$postData = json_decode($rawData, true);
// แปลง json เป็น อาเร หรือออปเจก php

$cus_id = isset($postData['cus_id']) ? $postData['cus_id'] : '';


$sql = "SELECT * FROM customers WHERE cus_id = " . $cus_id . "";
$rs = $conn->query($sql);

// print_r($rs);


if ($rs->num_rows > 0) {
    $customers = $rs->fetch_assoc();
}

$conn->close();

$resp = [
    'status' => 'success',
    'message' => '',
    'data' => [
        'customers' => $customers
    ]
];
echo json_encode($resp);
//แปลง อาเรresp เป็น json