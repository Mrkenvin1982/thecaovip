<?php
include '../config.php';

// get post data
foreach($_GET as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}

$id = intval($_GET['id']);
$price = intval($_GET['price']);
$status = intval($_GET['status']);
$pin = $_GET['pin'];
$seri = $_GET['seri'];

$models_phones = new Models_Phones();
$phones = $models_phones->getObjectByCondition('', array('id' => $id, 'status' => 2));

if(is_object($phones)) {
    if($price > 0) {
        $db = Models_Db::getDBO();
        $db->beginTransaction();
        $phones = $models_phones->getObject($id, 1);
        $phones->dathanhtoan = $phones->dathanhtoan + $price;
        $phones->canthanhtoan = $phones->canthanhtoan - $price;
        $phones->status = $status;
        $models_phones->setPersistents($phones);
        $models_phones->edit(array('canthanhtoan', 'dathanhtoan', 'status'), 1);
        $db->commit();
        
        $cards = new Persistents_Cards();
        $cards->phone_id = $id;
        $cards->pin = $pin;
        $cards->seri = $seri;
        $cards->price = $price;
        $cards->time = time();
        $cards->status = 1;
        $models_cards = new Models_Cards($cards);
        $models_cards->add();
    }
    else {
        $phones->status = $status;
        $models_phones->setPersistents($phones);
        $models_phones->edit(array('status'), 1);
    }
    
    echo json_encode(array('code' => 0, 'msg' => 'success!'));
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'Obj not found!'));
}