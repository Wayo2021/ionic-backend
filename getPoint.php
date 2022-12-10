
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

$tot_id = isset($postData['tot_id']) ? $postData['tot_id'] : '';


$sql = "SELECT * FROM point_total where tot_id = " . $tot_id . "";
$rs = $conn->query($sql);

// print_r($rs);


if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
        $point_total[] = $row;
    }
}

$conn->close();

$resp = [
    'status' => 'success',
    'message' => '',
    'data' => [
        'point_total' => $point_total
    ]
];
echo json_encode($resp);
//แปลง อาเรresp เป็น json
