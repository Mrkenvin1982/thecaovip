<?php
include '../config.php';

$id = intval($_POST['id']);

$models_phones = new Models_Phones();
$phones = $models_phones->getObject($id);
if(is_object($phones)) {
    if(($phones->canthanhtoan == 0 && $phones->dathanhtoan == 0) || ($phones->canthanhtoan > 0 && $phones->user_8pay > 0)) {
        // xoa tat ca du lieu card
        $models_cards = new Models_Cards();
        $models_cards->customDelete(array('phone_id' => $id));
        $models_phones->delete($id);
        echo json_encode(array('code' => 0, 'msg' => 'Cập nhật thành công!'));
    }
    else {
        echo json_encode(array('code' => 1, 'msg' => 'Không thể xoá!'));
    }
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'SDT không tồn tại!'));
}