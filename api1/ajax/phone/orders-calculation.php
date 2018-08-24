<?php 
include '../../config.php'; 
$data =$_POST['data_test'];
if (!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();	
}
$models_discount = new Models_DiscountPercentage();
$models_default = new Models_DefaultDiscount();
$models_prepaid = new Models_PrepaidDiscount();
$hotSaleObject = $models_prepaid->getObjectByCondition('',array('orders'=>4));
  $numof_vt =array('086', '096', '097', '098', '0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169');
  $numof_mb =array('090', '093', '0120', '0121', '0122', '0126', '0128', '089');
  $numof_vn =array('091', '094', '0123', '0124', '0125', '0127', '0129','088');
$user_discount = $models_discount->getObjectByCondition('',array('user_id'=>$adminuser->getId()));
$array_discount=array();
if (is_object($user_discount)) {
    $array_discount[1] = $user_discount->viettel_percent;
    $array_discount[2] = $user_discount->mobi_percent;
    $array_discount[3] = $user_discount->vina_percent;
    $array_discount[4] = $user_discount->ftth_percent;

}else{
$default_discount = $models_default->getLastObject()->discount;

 $array_discount[1] = $array_discount[2]=$array_discount[3]=$array_discount[4]=$default_discount;

}
$total=0;
$standard_total=0;
  foreach ($data as $values) {
  	foreach ($values as $key => $value) {
  		$$key=$value;
  	}
  	$type=intval($type);
$price=intval($price);
  	$total +=$price;

$first ='';
if (substr($phone,0, 2)=='09'||substr($phone,0, 2)=='08') {
    $first = substr($phone,0, 3);
}else{
    $first = substr($phone,0, 4);

}
if (in_array($first, $numof_vt)) {
    $provider = 1;
}elseif (in_array($first, $numof_mb)) {
    $provider = 2;
}elseif (in_array($first, $numof_vn)) {
    $provider = 3;
}elseif ($type==2) {
    $provider = 4;
}else {
       continue;
}
if (!in_array($type, array(0,1,2))) {
 continue;
}
    $real_discount =$array_discount[$provider];
    $prepaid_discount=0;
    if ($type==1) {
$prepaid = $models_prepaid->getObjectByCondition('',array('orders'=>$provider));
if (is_object($prepaid)) {
     $prepaid_discount=$prepaid->discount;
}
    }
$discount_percent=$real_discount-$prepaid_discount; 
$discount_percent+=$hotSaleObject->discount;
$discount_money=$price/100*$discount_percent;
$standard_total += $price-$discount_money;
  }
   echo json_encode(array('code' => 0, 'total' => number_format($total),'standard_total'=> number_format($standard_total)));
        exit();