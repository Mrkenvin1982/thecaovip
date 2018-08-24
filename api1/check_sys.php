<?php

include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$card_type_id = intval($_GET['card_type_id']);

$arr_type = array(
    1 => 'Viettel',
    //2 => 'Mobiphone',
    3 => 'Vinaphone'
);

if(!in_array($card_type_id, array_keys($arr_type))) {
    echo "---";
    exit();
}

$models_phones = new Models_Phones();
$total = $models_phones->getSumByColumn('canthanhtoan', array('status' => 1, 'loai' => $card_type_id, 'canthanhtoan' => array(0 , '>=')));
$max = $models_phones->getMaxByColumn('canthanhtoan', array('status' => 1, 'loai' => $card_type_id, 'canthanhtoan' => array(0 , '>=')));

?>
Chúng tôi cần : <?= number_format($total) ?> VND 
Với các thẻ <span class="text-danger"><?= $arr_type[$card_type_id] ?></span> mệnh giá <= <?= number_format($max) ?> VND. 
Nếu nạp thẻ chọn mệnh giá lỗi, vui lòng nạp thường và không chọn mệnh giá!<br/>
