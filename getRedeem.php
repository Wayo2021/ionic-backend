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

$cus_id = isset($postData['id']) ? $postData['id'] : '';

$sql = "SELECT redeem.rew_id, redeem.point_amount, redeem.get_status, redeem.cus_id, redeem.red_date, reward.rew_name, reward.image FROM redeem INNER JOIN reward ON redeem.rew_id = reward.rew_id  where redeem.cus_id = ". $cus_id . " AND redeem.get_status = 'รับของรางวัล'";
$rs = $conn->query($sql);

// print_r($rs);


if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
        $redeem[] = $row;
    }
}

$conn->close();

$resp = [
    'status' => 'success',
    'message' => '',
    'data' => [
        'redeem' => $redeem
    ]
];
echo json_encode($resp);
//แปลง อาเรresp เป็น json