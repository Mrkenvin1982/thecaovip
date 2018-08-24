<?php
include '../config.php';
header("Content-Type: application/json;charset=utf-8");

$user_8pay = intval($_GET['user_8pay']);
$models_phones = new Models_Phones();
$list = $models_phones->customFilter('', array('userid' => 1, 'user_8pay' => $user_8pay, 'status' => array(5, '!=')));

foreach ($list as $obj) {
    $arr[] = array(
        'id' => $obj->getId(),
        'phone' => $obj->phone,
        'type' => $obj->type,
        'canthanhtoan' => $obj->canthanhtoan,
        'dathanhtoan' => $obj->dathanhtoan,
        'time' => $obj->time,
        'status' => $obj->status
    );
}

echo json_encode($arr);