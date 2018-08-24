<?php 
include '../../config.php'; 


$arr_true = $_POST['arr_true_id'];
$all=$arr_true["'all'"];
foreach($_POST as $key => $value) {
    $$key = $value;

}
if(!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}


$models_user = new Models_Users();
 $models_histories = new Models_Histories();
    $checkid='';
if (isset($phone)) {
$uss = $models_user->getObjectByCondition('',array('phone'=>$phone));
if (is_object($uss)) {
  $checkid =" and userid = {$uss->getId()}";
}
}

    $cst='';
if ($status!=100) {
        if($status==99){
                     $cst= " and canthanhtoan =0 ";
                }
                elseif ($status==-1) {
                    $cst = " and canthanhtoan <0 ";
                }
                else {
                     $cst = " and (status = $status and canthanhtoan >0 ) ";

            
                }
}

 $ct='';
if ($type!=100) {
if (in_array($type, array(0,1,2))) {
  $ct=" and type = $type";
}if (in_array($type, array(10,11))) {
switch ($type) {
  case 10:
   $ct=" and gop = 0";
    break;
  
  case 11:
   $ct=" and gop = 1";
   
    break;
}
}
}  

$target=strtoupper($target);

if (substr($target, 0,1)=='T') {
$target = substr($target, 1);
$checkst= "id = '{$target}' and";
}else{
$target = substr($target, 1);
   $checkst= "(id LIKE '%{$target}%' or phone LIKE '%{$target}%') and";
}

$models_phones = new Models_Phones();

$arr_st = explode('/', trim($start_date));
$start_date = mktime(0,0,0,$arr_st[1],$arr_st[0],$arr_st[2]);

$arr_en = explode('/', trim($end_date));
$end_date = mktime(23,59,59,$arr_en[1],$arr_en[0],$arr_en[2]);
$checkuser ='';
if ($adminuser->group_id!=1) {
  $checkuser =" and userid = {$adminuser->getId()}";
}

$query_total = "SELECT * FROM Phones WHERE $checkst time BETWEEN $start_date and $end_date $checkid $checkuser $cst $ct order by id desc"; 

$listId= array();
$listfull = $models_phones->customQuery($query_total);
foreach ($listfull as $value) {
     if (isset($arr_true["'{$value->getId()}'"])) {
      if ($arr_true["'{$value->getId()}'"]==1) {
     array_push($listId,'T'.$value->getId());
      }
      continue;
  }
     if ($all==1) {
     array_push($listId,'T'.$value->getId());
   continue;
}
}
if (count($listId)>0) {
   echo json_encode(array('code' => 0, 'msg' => implode(', ', $listId)));
    exit();
}
  echo json_encode(array('code' => 1, 'msg' => 'Không có đơn hàng nào được chọn'));
    exit();