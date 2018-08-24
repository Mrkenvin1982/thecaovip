<?php
include '../config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// get post data
foreach($_GET as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}

$canthanhtoan = intval($canthanhtoan);
$type = intval($type);
$user_8pay = intval($user_8pay);

// check phone
if(!Library_Validation::isPhoneNumber($phone)) {
    echo json_encode(array('code' => 1, 'msg' => 'SDT không hợp lệ!'));
    exit();
}

if($canthanhtoan < 10000 || $canthanhtoan%10000 !== 0) {
    echo json_encode(array('code' => 1, 'msg' => 'Số tiền cần thanh toán phải là bội số của 10,000!'));
    exit();
}

$phones = new Persistents_Phones();
$phones->phone = $phone;
$phones->loai = 1;
$phones->type = $type;
$phones->canthanhtoan = $canthanhtoan;
$phones->userid = 1;
$phones->user_8pay = $user_8pay;
$phones->time = time();
$phones->status = 1;

$models_phones = new Models_Phones($phones);
$last_id = $models_phones->add(1);
if($last_id) {
    echo json_encode(array('code' => 0, 'msg' => 'Thanh cong!', 'phone_id' => $last_id));
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'SĐT đã tồn tại trong hệ thống!'));
}