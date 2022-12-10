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

$rew_id = isset($postData['id']) ? $postData['id'] : '';


$sql = "SELECT * FROM reward where rew_id = ". $rew_id ."";
$rs = $conn->query($sql);

// print_r($rs);


if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
        $reward[] = $row;
    }
}

$conn->close();

$resp = [
    'status' => 'success',
    'message' => '',
    'data' => [
        'reward' => $reward
    ]
];
echo json_encode($resp);
//แปลง อาเรresp เป็น json