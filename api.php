<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT,PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');
header('Content-Length: 0');
header('Content-Type:  text/plain');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, Accept, X-Requested-With, x-xsrf-token');
header('Content-Type: application/json; charset-utf-8');

include 'config.php';

$postJSON = json_decode(file_get_contents('php://input'),true);

if ($postJSON['action'] == 'register') {

    $usernamecheck = mysqli_fetch_array(mysqli_query($con, "SELECT username FROM users WHERE username = '$postJSON[username]'"));

    if ($usernamecheck['username'] == $postJSON['username']) {
        $result = json_encode(array('success' => false, 'msg' => 'username Sudah Terdaftar'));
    } else {
        $insert = mysqli_query($con, "INSERT INTO users
                firstname = '$postJSON[firstname]',
                lastname = '$postJSON[lastname]',
                username = '$postJSON[username]',
                password = '$postJSON[password]'
                ");
        if ($insert) {
            $result = json_encode(array('success' => true, 'msg' => 'Register Success!!'));
        } else {
            $result = json_encode(array('Failed' => false, 'msg' => 'Register Failed!!'));
        }
    }
    echo $result;
}
