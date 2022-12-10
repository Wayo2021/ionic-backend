<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

$conn = mysqli_connect("localhost", "root", "", "app_drink_reward");
mysqli_set_charset($conn, "utf8");


// mysqli_query($conn,);
// mysqli_query($conn,"SET CHARACTER SET 'utf8'");
//mysqli_query($conn,"SET SESSION collation_connection ='utf8_general_ci'");
// mysqli_query($conn,"SET NAMES utf8");
//mysqli_set_charset($conn,"utf8");
//mysqli_set_charset($objCon, "utf8");
//$conn>set_charset("utf8");

if (!$conn) {
    $res = [
        'status' => 'error',
        'message' => 'can not connect database'
    ];
    echo json_encode($res);
    exit;
}

$sql = "
        SELECT * FROM types;
        ";
$rs = $conn->query($sql);

$data = [];
if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

$resp = [
    'status' => 'success',
    'message' => '',
    'data' => [
        'listTypes' => $data
    ]
];

echo json_encode($resp, JSON_UNESCAPED_UNICODE);
