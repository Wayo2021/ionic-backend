<?php
header("Access-Control-Allow-Origin: *");

$conn = mysqli_connect("localhost", "root", "", "app_drink_reward");
mysqli_set_charset($conn, "utf8");
date_default_timezone_set('Asia/Bangkok');
if (!$conn) {
    $res = [
        'status' => 'error',
        'message' => 'can not connect database',
    ];
    printResponse($res);
    // return;
}

$rawData = file_get_contents("php://input");
$postData = json_decode($rawData, true);

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
$cus_point = isset($postData['cus_point']) ? $postData['cus_point'] : '';
// $profileMember = isset($postData['profileMember']) ? $postData['profileMember'] : '';
// $cus_status = isset($postData['cus_status']) ? $postData['cus_status'] : '';

// $base64Image = $postData['profileMember'];
// $fileName = uniqid();

// try {
// $splited = explode(',', substr($base64Image, 5), 2);
// $mime = $splited[0];
// $data = $splited[1];

// $mime_split_without_base64 = explode(';', $mime, 2);
// $mime_split = explode('/', $mime_split_without_base64[0], 2);
// if (count($mime_split) == 2) {
//     $extension = $mime_split[1];
//     if ($extension == 'jpeg') {
//         $extension = 'jpg';
//     }
//     $fileName = $fileName . '.' . $extension;
// }

// file_put_contents("upload/" . $fileName, base64_decode($data));

// $resp = [
//     'status' => 'success',
//     'message' => '',
//     'data' => [
//         'image' => "http://localhost/unseen/upload/" . $fileName
//     ]
// ];

$sql4 = "INSERT INTO point_total (tot_id, tot_date, cus_phone)
            VALUES ('" . $tot_id . "','" . date("Y-m-d H:i:s") . "','" . $cus_phone . "')";
$result = mysqli_query($conn, $sql4) or die(mysqli_error($conn));

$sql2 = "SELECT tot_id,tot_point FROM point_total ORDER BY tot_id DESC LIMIT 1";
$result = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
$row2 = mysqli_fetch_array($result);

$statusNew = "normal";
$sql3 = "INSERT INTO customers (cus_id, cus_first_name, cus_last_name, cus_email, cus_password, cus_phone, tot_id, cus_status,cus_point)
            VALUES ('" . $cus_id . "','" . $cus_first_name . "','" . $cus_last_name . "','" . $cus_email . "','" . $cus_password . "','" . $cus_phone . "','" . $row2['tot_id'] . "','" . $statusNew . "','" . $row2['tot_point'] . "')";
$result = mysqli_query($conn, $sql3) or die(mysqli_error($conn));


if ($conn->query($sql3) === TRUE) {
    $res = [
        'status' => 'success',
        'message' => ''
    ];
    // http_response_code(201);
    // $message['status'] = "Success";
    printResponse($res);
    return;
}else{
    $res = [
        'status' => 'error',
        'message' => $conn->error
    ];
    // http_response_code(422);
    // $message['status'] = "Error";
    printResponse($res);
    return;
}

// } catch (\Throwable $th) {
//     $resp = [
//         'status' => 'fail',
//         'message' => 'upload image error',
//         'data' => null
//     ];
//     printResponse($resp);
//     return;
// }

function printResponse($response){
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($response);
}
// echo json_encode($message);