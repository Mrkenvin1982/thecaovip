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

$id = intval($id);
$models_phones = new Models_Phones();
$phones = $models_phones->getObject($id);
if(is_object($phones)) {
    if($action == "del") {
        if($phones->status == 0 || $phones->status == 3 || $phones->canthanhtoan <= 0) {
            $phones->status = 5;
            $models_phones->setPersistents($phones);
            $models_phones->edit(array('status'), 1);
            
            $return_price = $phones->canthanhtoan <= 0 ? 0 : $phones->canthanhtoan;
            echo json_encode(array('code' => 0, 'msg' => 'success', 'phone_id' => $phones->getId(), 'return_price' => $return_price));
        }
        else {
            echo json_encode(array('code' => 1, 'msg' => 'Thao tac fail'));
        }
    }
    elseif($action == "start") {
        if($phones->status == 0) {
            $phones->status = 1;
            $models_phones->setPersistents($phones);
            $models_phones->edit(array('status'));
            echo json_encode(array('code' => 0, 'msg' => 'Cập nhật thành công!'));
        }
        else {
            echo json_encode(array('code' => 1, 'msg' => 'Thao tác sai!'));
        }
    }
    elseif($action == "pause") {
        if($phones->status == 1) {
            $phones->status = 0;
            $models_phones->setPersistents($phones);
            $models_phones->edit(array('status'));
            echo json_encode(array('code' => 0, 'msg' => 'Cập nhật thành công!'));
        }
        else {
            echo json_encode(array('code' => 1, 'msg' => 'Thao tác sai!'));
        }
    }
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'SĐT không tồn tại!'));
}