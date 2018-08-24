<?php 

include '../config.php';
$model_card  = new Models_CardStores();
$card = $model_card->getLastObjectByCondition(array('status'=>1,'card_type'=>1));
/*
        $seri=Library_String::decryptstr($card->seri,SERI_PASS);
        $pin=Library_String::decryptstr($card->pin,PIN_PASS);
        $price = $card->price;*/
        $seri=142423423423423;
        $pin=423423423432423;
        $price=10000000;
$fields = array(
'pin' => $pin,
'seri' => $seri,
'price' => $price
);
echo "http://127.0.0.1:29995/api/charging?" . http_build_query($fields);
exit;

$json = file_get_contents("http://127.0.0.1:29995/api/charging?" . http_build_query($fields));
$decode = json_decode($json);

$status =$decode->status;
$msg = $decode->msg;
$code = $decode->code;
$card->result = $msg;

if ($code==30) {
	$card->status = 1;
}else{
	if ($status==true) {
		$card->status = 1;
	}else{
		$card->status = 2;
	}
}
$card->time_out=time();
/*echo $card->status;
exit;*/
$model_card->setPersistents($card);

if ($model_card->edit(array('result','status','time_out'),1)!==false) {
	echo "Cập nhật thành công<br>message: $msg";
}else{
	echo "Cập nhật thất bại <br>message: $msg";


}

 ?>
