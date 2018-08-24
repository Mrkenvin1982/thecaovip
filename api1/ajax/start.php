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

$models_phones = new Models_Phones();
$phones = $models_phones->getObject(intval($id));
if(is_object($phones) && $phones->status == 0) {
    $phones->status = 1;
    $models_phones->setPersistents($phones);
    $models_phones->edit(array('status'), 1);
    echo json_encode(array('code' => 0, 'msg' => 'Cập nhật thành công!'));
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'SĐT đang bật rồi!'));
}