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
// แปลง json เป็น อาเรย์ หรือออปเจก php

$typs_id = isset($postData['typs_id']) ? $postData['typs_id'] : '';


$sql = "SELECT * FROM types";
$rs = $conn->query($sql);

// print_r($rs);


if ($rs->num_rows > 0) {
    $types = $rs->fetch_assoc();
}

$conn->close();

$resp = [
    'status' => 'success',
    'message' => '',
    'data' => [
        'types' => $types
    ]
];
echo json_encode($resp);
//แปลงอาเรย์ resp เป็น json