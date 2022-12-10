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

$typs_id = isset($postData['id']) ? $postData['id'] : '';


$sql = "SELECT drink.drk_name,drink.drk_price,drink.image,types.typs_name,size.siz_name FROM drink 
INNER JOIN types ON drink.typs_id = types.typs_id 
INNER JOIN size ON drink.siz_id = size.siz_id 
where drink.typs_id = " . $typs_id . "";
$rs = $conn->query($sql);

// print_r($rs);


if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
        $drink[] = $row;
    }
}

$conn->close();

$resp = [
    'status' => 'success',
    'message' => '',
    'data' => [
        'drink' => $drink
    ]
];
echo json_encode($resp);
//แปลง อาเรresp เป็น json