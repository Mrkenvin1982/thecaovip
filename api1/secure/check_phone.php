<?php
include '../config.php';

$price = intval($_GET['price']);
$loai = 1;

$models_phones = new Models_Phones();
if($price > 0) {
    $list = $models_phones->customQuery("SELECT * FROM Phones WHERE canthanhtoan >= {$price} AND loai = {$loai} AND status = 1 AND type BETWEEN 2 AND 3 ORDER BY id ASC");
}
else {
    $list = $models_phones->customQuery("SELECT * FROM Phones WHERE canthanhtoan >= 1000000 AND loai = {$loai} AND status = 1 AND type BETWEEN 2 AND 3 ORDER BY id ASC");
}

foreach ($list as $obj) {
    $min = $obj->canthanhtoan - $price;
    if($obj->gop === 0 && $min === 0) {
        $arr_kgop[] = $obj;
    }
    else {
        $arr_gop[] = $obj;
    }
}

if(count($arr_kgop) > 0) {
    $obj = $arr_kgop[0];
}
elseif(count($arr_gop) > 0) {
    $obj = $arr_gop[0];
}
else {
    $obj = new Persistents_Phones();
}

if($obj->phone) {
    // update phone nay ve trang thai dang xu ly
    $obj->status = 2;
    $models_phones->setPersistents($obj);
    $models_phones->edit(array('status'), 1);
    echo json_encode(array('phone' => $obj->phone, 'canthanhtoan' => $obj->canthanhtoan, 'type' => $obj->type, 'id' => $obj->getId()));
}
else {
    echo json_encode(array('id' => FALSE));
}
