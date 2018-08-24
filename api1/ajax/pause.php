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

$id = intval($id);

$models_phones = new Models_Phones();
$phones = $models_phones->getObject($id);
if(is_object($phones) && $phones->status == 1) {
    $phones->status = 0;
    $models_phones->setPersistents($phones);
    $models_phones->edit(array('status'), 1);
    echo json_encode(array('code' => 0, 'msg' => 'Cập nhật thành công!'));
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'SĐT đang được xử lý, không thể thực hiện thao tác này!'));
}