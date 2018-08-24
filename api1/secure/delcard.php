<?php
include '../config.php';

$cardid = intval($_POST['cardid']);
$models_cards = new Models_Cards();
$cards = $models_cards->getObject($cardid);

if(!is_object($cards)) {
    echo json_encode(array('code' => 1, 'msg' => 'Card không tồn tại!'));
    exit();
}

$models_phones = new Models_Phones();
$phones = $models_phones->getObject($cards->phone_id);

if(!is_object($phones)) {
    echo json_encode(array('code' => 1, 'msg' => 'Phone không tồn tại!'));
    exit();
}

if($models_cards->delete($cardid)) {
    $canthanhtoan = $phones->canthanhtoan;
    $dathanhtoan = $phones->dathanhtoan;
    $update_canthanhtoan = $canthanhtoan + intval($cards->price);
    $update_dathanhtoan = $dathanhtoan - intval($cards->price);  
    
    $phones->canthanhtoan = $update_canthanhtoan;
    $phones->dathanhtoan = $update_dathanhtoan;
    
    $models_phones->setPersistents($phones);
    if($models_phones->edit(array('dathanhtoan', 'canthanhtoan'), 1)) {
        echo json_encode(array('code' => 0, 'msg' => 'Thanh cong!'));
    }
    else {
        echo json_encode(array('code' => 1, 'msg' => 'Khong cap nhat duoc thanh toan!'));
    }
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'Khong delete dc!'));
}