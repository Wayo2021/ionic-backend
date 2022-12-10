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
$data = json_decode($rawData, true);

$red_id = isset($data['red_id']) ? $data['red_id'] : '';
$red_date = isset($data['red_date']) ? $data['red_date'] : '';
$rew_amount = isset($data['rew_amount']) ? $data['rew_amount'] : '';
$cus_id = isset($data['cus_id']) ? $data['cus_id'] : '';
$rew_id = isset($data['rew_id']) ? $data['rew_id'] : '';
$poi_id = isset($data['poi_id']) ? $data['poi_id'] : '';
$emp_id = isset($data['emp_id']) ? $data['emp_id'] : '';
$red_status = isset($data['red_status']) ? $data['red_status'] : '';
$point_amount = isset($data['point_amount']) ? $data['point_amount'] : '';
$get_status = isset($data['get_status']) ? $data['get_status'] : '';
$cus_phone = isset($data['cus_phone']) ? $data['cus_phone'] : '';

$status = 'รับของรางวัล';
$amount = 1;

//ตารางคะแนน
$slt_poitot1 = "SELECT cus_phone, tot_point FROM point_total WHERE cus_phone = '" . $cus_phone . "'";
$qry_poitot1 = mysqli_query($conn, $slt_poitot1) or die(mysqli_error($conn));
$row_poitot1 = mysqli_fetch_array($qry_poitot1);

//ตารางของรางวัล
$slt_rew1 = "SELECT rew_remain, rew_id, rew_point FROM reward WHERE rew_id = '" . $rew_id . "'";
$qry_rew1 = mysqli_query($conn, $slt_rew1) or die(mysqli_error($conn));
$row_rew1 = mysqli_fetch_array($qry_rew1);
$point = $row_rew1['rew_point'];

//check point
// if ($row_poitot1['tot_point'] < 40) {
//   $res = [
//     'status' => 'error5',
//     'message' => $conn->error
//   ];
//   printResponse($res);
//   return;
    
if ($row_poitot1["cus_phone"] !== $cus_phone) {

    $res = [
        'status' => 'error5',
        'message' => $conn->error
    ];
    printResponse($res);
    return;
	// echo "<script>alert('เบอร์โทรนี้ไม่มีผู้ใช้งาน');</script>";
	// echo "<script type='text/javascript'> document.location ='../view/checkout_reward.php'; </script>";
	
} elseif ($row_rew1['rew_remain'] == 0) {
    $res = [
        'status' => 'error3',
        'message' => $conn->error
    ];
    printResponse($res);
    return;

	// echo "<script>alert('คะแนนสะสมไม่เพียงพอต่อการแลกของรางวัล');</script>";
	// 	echo "<script type='text/javascript'> document.location ='../view/show_redeem.php.php?error=แลก ของรางวัลไม่สำเร็จ'; </script>";
	// 	header("location: ../view/checkout_reward.php?error=แลก ของรางวัลไม่สำเร็จ เนื่องจากของรางวัลไม่เพียงพอ");
		
} elseif ($row_poitot1['tot_point'] < 40) {
    $res = [
        'status' => 'error2',
        'message' => $conn->error
    ];
    printResponse($res);
    return;

	// echo "<script>alert('ของรางวัลมีไม่เพียงพอ');</script>";
	// echo "<script type='text/javascript'> document.location ='../view/show_redeem.php.php?error=แลก ของรางวัลไม่สำเร็จ'; </script>";
	// header("location: ../view/checkout_reward.php?error=แลก ของรางวัลไม่สำเร็จ เนื่องจากคะแนนสะสมไม่เพียงพอ");
	
} elseif ($row_poitot1['tot_point'] >= 40 and $row_rew1['rew_remain'] >= 1) {

    $sumtotal_point = $row_poitot1['tot_point'] - $point;
    //ตารางคะแนน
    $up_poitot1 = "UPDATE point_total SET tot_date = '" . date("Y-m-d H:i:s") . "', tot_point = '" . $sumtotal_point . "' WHERE cus_phone = '" . $cus_phone . "'";
    $qry_poitot2 = mysqli_query($conn, $up_poitot1) or die(mysqli_error($conn));

    //ตารางลูกค้า
    $up_Cus1 = "UPDATE customers SET cus_point = '" . $sumtotal_point . "' WHERE cus_phone = '" . $cus_phone . "'";
    $qry_Cus1 = mysqli_query($conn, $up_Cus1) or die(mysqli_error($conn));

    //ตารางลูกค้า
    $slt_Cus1 = "SELECT cus_id FROM customers WHERE cus_phone = '" . $cus_phone . "'";
    $qry_Cus1 = mysqli_query($conn, $slt_Cus1) or die(mysqli_error($conn));
    $row_Cus1 = mysqli_fetch_array($qry_Cus1);

    //ตารางแลกของรางวัล
    $strSQL = "INSERT INTO redeem (rew_id, rew_amount, red_date, cus_id, point_amount, get_status)
					VALUES('" . $rew_id . "','" . $amount . "','" . date("Y-m-d H:i:s") . "' ,'" . $row_Cus1["cus_id"] . "','" . $point . "','" . $status . "')";
    mysqli_query($conn, $strSQL) or die(mysqli_error($conn));

    //ตารางของรางวัล
    $slt_rew2 = "SELECT rew_remain, rew_id FROM reward WHERE rew_id = '" . $rew_id . "'";
    $qry_rew2 = mysqli_query($conn, $slt_rew2) or die(mysqli_error($conn));
    $row_rew2 = mysqli_fetch_array($qry_rew2);

    $sumtotal_reward = $row_rew2['rew_remain'] - $amount;
    //ตารางของรางวัล
    $up_rew1 = "UPDATE reward SET rew_remain = '" . $sumtotal_reward . "' WHERE rew_id = '" . $rew_id . "'";
    $qry_rew3 = mysqli_query($conn, $up_rew1) or die(mysqli_error($conn));

    if ($qry_rew3 == TRUE) {

        $res = [
            'status' => 'success',
            'message' => ''
        ];
        // http_response_code(201);
        // $message['status'] = "Success";
        printResponse($res);
        return;
    } else {
        echo "<script>alert('มีบางอย่างผิดพลาด กรุณาลองอีกครั้ง');</script>";
    }
} else {
    $res = [
        'status' => 'error1',
        'message' => $conn->error
    ];
    // http_response_code(422);
    // $message['status'] = "Error";
    printResponse($res);
    return;
}


if ($conn->query($sql3 and $qry_rew3) === TRUE) {
    $res = [
        'status' => 'success',
        'message' => ''
    ];
    printResponse($res);
    return;
} else {
    $res = [
        'status' => 'error',
        'message' => $conn->error
    ];
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

function printResponse($response)
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($response);
}
// echo json_encode($message);

// $sql2 = "SELECT cus_id, cus_phone FROM customers where cus_phone = '" . $cus_phone . "'";
// $result = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
// $row2 = mysqli_fetch_array($result);

// $sql4 = "SELECT rew_id, rew_point, rew_remain FROM reward where rew_id = '" . $rew_id . "'";
// $result = mysqli_query($conn, $sql4) or die(mysqli_error($conn));
// $row3 = mysqli_fetch_array($result);

// $status = 'รับของรางวัล';
// $amount = 1;
// $point = $row3["rew_point"];
// $strSQL = "INSERT INTO redeem (rew_id, rew_amount, red_date, cus_id, point_amount, get_status) VALUES('" . $rew_id . "','" . $amount . "','" . date("Y-m-d H:i:s") . "','" . $row2["cus_id"] . "','" . $point . "','" . $status . "')";
// $sql3 = mysqli_query($conn, $strSQL) or die(mysqli_error($conn));

// $remain = $row3["rew_remain"] - $amount;
// $up_rew1 = "UPDATE reward SET rew_remain = '" . $remain . "' WHERE rew_id = '" . $rew_id . "'";
// $qry_rew3 = mysqli_query($conn, $up_rew1) or die(mysqli_error($conn));

// $remain_point = $row3["rew_remain"] - $amount;
// $up_rew1 = "UPDATE reward SET rew_remain = '" . $remain . "' WHERE rew_id = '" . $rew_id . "'";
// $qry_rew3 = mysqli_query($conn, $up_rew1) or die(mysqli_error($conn));
