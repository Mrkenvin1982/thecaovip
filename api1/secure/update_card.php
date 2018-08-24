<?php
include '../config.php';

// get post data
foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}
// check phone
$models_phones = new Models_Phones();
$phones = $models_phones->getObject(intval($phone_id));

if(!is_object($phones)) {
    echo json_encode(array('code' => 1, 'msg' => 'Phone id không tồn tại!'));
    exit();
}

if($pin == "") {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa nhập mã thẻ!'));
    exit();
}

if($seri == "") {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa nhập mã seri!'));
    exit();
}

// check menh gia
$arr_price = array(10000, 20000, 30000, 50000, 100000, 200000, 300000, 500000, 1000000);
if(!in_array(intval($price), $arr_price)) {
    echo json_encode(array('code' => 1, 'msg' => 'Mệnh giá không phù hợp!'));
    exit();
}

$cards = new Persistents_Cards();
$cards->phone_id = $phones->getId();
$cards->pin = $pin;
$cards->seri = $seri;
$cards->price = intval($price);
$cards->time = time();
$cards->status = 1;
$models_cards = new Models_Cards($cards);
if($models_cards->add()) {
    $canthanhtoan = $phones->canthanhtoan;
    $dathanhtoan = $phones->dathanhtoan;
    $update_canthanhtoan = $canthanhtoan - intval($price);
    $update_dathanhtoan = $dathanhtoan + intval($price);  
    
    $phones->canthanhtoan = $update_canthanhtoan;
    $phones->dathanhtoan = $update_dathanhtoan;
    
    $models_phones->setPersistents($phones);
    if($models_phones->edit(array('dathanhtoan', 'canthanhtoan'), 1)) {
        echo json_encode(array('code' => 0, 'msg' => 'Thanh cong!'));
    }
    else {
        echo json_encode(array('code' => 1, 'msg' => $models_phones->getSql()));
    }
}
else {
    echo json_encode(array('code' => 1, 'msg' => $models_cards->getSql()));
}