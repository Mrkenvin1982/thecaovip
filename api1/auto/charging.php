<?php 
set_time_limit(4000);

include '../config.php';
$model_card  = new Models_CardStores();
$card = $model_card->getLastObjectByCondition(array('status'=>1,'card_type'=>1));
if (is_object($card)) {
        $seri=Library_String::decryptstr($card->seri,SERI_PASS);
        $pin=Library_String::decryptstr($card->pin,PIN_PASS);
        $price = $card->price;
$fields = array(
'pin' => $pin,
'seri' => $seri,
'price' => $price
);
$card->status=4;
$model_card->setPersistents($card);
$model_card->edit(array('status'),1);
/*echo "http://123.17.153.233:29995/api/charging?" . http_build_query($fields);
exit;*/
$json = file_get_contents("http://123.17.153.33:29995/api/charging?" . http_build_query($fields));

$decode = json_decode($json);
/*print_r($decode);
*/

if (is_object($decode)) {

$code = $decode->code;
$status =$decode->status;
$msg = $decode->msg;
$card->result = $msg;
if ($code==30) {
	$card->status = 3;
}elseif ($code==1) {
	$card->status = 0;
}else{
$card->status = 2;
}
$card->time_out=time();
/*echo $card->status;
exit;*/
$model_card->setPersistents($card);
if ($model_card->edit(array('result','status'),1)!==false) {
	echo "Cập nhật thành công<br>message: $msg";
}else{
	echo "Cập nhật thất bại <br>message: $msg";


}
		echo "vào api";
}else{

	$card->status=1;
$model_card->setPersistents($card);
$model_card->edit(array('status'),1);
		echo "vào  sai api";
exit;
}
}
else{

	echo "Không có thẻ cào!";
}
 ?>
