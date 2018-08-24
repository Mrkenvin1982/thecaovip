<?php 
include '../../config.php'; 


$arr_true = $_POST['arr_true_id'];
$all=$arr_true["'all'"];
foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}
if(!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}


$models_user = new Models_Users();
$models_commis =new Models_CommissionHistories();
$models_histories = new Models_Histories();
$models_phones = new Models_Phones();
$models_dhis = new Models_DiscountHistories();
$models_discount = new Models_DiscountPercentage();
$models_default = new Models_DefaultDiscount();
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


$listfull = $models_phones->customQuery($query_total);
$success=array();
$errorrs=array();
foreach ($listfull as $value) {
 if ($value->canthanhtoan>0) {
/*  start*/
    $check=0;
     if (isset($arr_true["'{$value->getId()}'"])) {
      if ($arr_true["'{$value->getId()}'"]==1) {
        $check=1;
      }
  }elseif ($all==1) {
      $check=1;
}
if ($check) {
  if ($value->status==1&&$action=='pause') {
    $value->status = 0;
    $models_phones->setPersistents($value);
    $models_phones->edit(array('status'), 1);
    array_push($success, 'T'.$value->getId());
  }elseif ($value->status==0&&$action=='start') {
    $value->status = 1;
    $models_phones->setPersistents($value);
    $models_phones->edit(array('status'), 1);
    array_push($success, 'T'.$value->getId());
  }elseif (($value->status == 0 || $value->status == 3) && $value->canthanhtoan > 0&&$action=='refund') {
       
    //mới
    $dhis = $models_dhis->getObjectByCondition('',array('phone_id'=>$value->getId()));
$money = $dhis->money;
$percent =100-$dhis->discount_percent;

$canthanhtoan =$value->canthanhtoan;
$money_ref = $canthanhtoan/100*$percent;


        $file_log = "refund_money.log";
        Library_Log::writeOpenTable($file_log);
        Library_Log::writeHtml("Admin : " . $adminuser->getId(), $file_log);
        Library_Log::writeHtml("Refund for : {$value->phone}, Money : {$money_ref}", $file_log);

        // cong tien 
        $db = Models_Db::getDBO();
        $db->beginTransaction();
        $users = $models_users->getObject($value->userid, 1);
        $current_balance = $users->balance;
        $update_balance = $current_balance + $money_ref;

        Library_Log::writeHtml("Balance : " . number_format($current_balance), $file_log);
        Library_Log::writeHtml("Update Balance : " . number_format($update_balance), $file_log);

        $users->balance = $update_balance;
        $models_users->setPersistents($users);
        $models_users->edit(array('balance'), 1);

        // them vao lich su cong tien

        $histories = new Persistents_Histories();
               $histories->user_id =$users->getId();
    $histories->cur_balance = $current_balance;
    $histories->money = $money_ref;
    $histories->up_balance = $update_balance;
    $histories->time = time();
    $histories->note = json_encode(array('uid'=>$users->getId(),'msg'=>'Back tiền từ gd: T'.$value->getId()));
    $histories->orders = 4;
    $histories->status = 1;
     
        $models_histories->setPersistents($histories);
        $models_histories->add();
        

        //nếu có hoa hồng
            if ($value->dathanhtoan>0&&$users->refer!=0) {
            $fuser = $models_users->getObject($users->refer, 1);
          
            $fdiscount = $models_discount->getObjectByCondition('',array('user_id'=>$fuser->getId()));
    if (is_object($fdiscount)) {
               switch ($value->loai) {
               case 1:
                  $fdis = $fdiscount->viettel_percent;
                   break;
               case 2:
                  $fdis = $fdiscount->mobi_percent;
                  
                   break;
                case 3:
                  $fdis = $fdiscount->vina_percent;
                   break;   
           }
        if ($type==2) {
           $fdis = $fdiscount->ftth_percent;
        }
    }else{
          $default_discount = $models_default->getLastObject()->discount;
            $fdis=$default_discount;
    }
   $fpercent = $fdis-$dhis->real_discount;
if ($fpercent>0) {
  $fdiscount_money = $value->dathanhtoan/100*$fpercent;
 $fdiscount_money<0?$fdiscount_money=0:0;
            $fcurent_balance = $fuser->balance;
            $fupdate_balance = $fcurent_balance+$fdiscount_money;
   $fuser->balance = $fupdate_balance;
        $models_users->setPersistents($fuser);
        $models_users->edit(array('balance'), 1);
    $histories = new Persistents_Histories();
     $histories->user_id =$fuser->getId();
    $histories->cur_balance = $fcurent_balance;
    $histories->money = $fdiscount_money;
    $histories->up_balance = $fupdate_balance;
    $histories->time = time();
    $histories->note = json_encode(array('uid'=>$users->getId(),'msg'=>' Tiền hoa hồng từ gd: T'.$value->getId()));
    $histories->orders = 5;
    $histories->status = 1;
     
        $models_histories->setPersistents($histories);
        $models_histories->add();
        $commiss = new Persistents_CommissionHistories();
        $commiss->user_id=$fuser->getId();
        $commiss->discount=$fpercent;
        $commiss->money=$fdiscount_money;
        $commiss->discount_histories_id=$dhis->getId();
        $commiss->time=time();
        $commiss->orders=1;
        $commiss->status=1;
$models_commis->setPersistents($commiss);
        $models_commis->add();
}

        }
$dhis->unpaid_amount=$canthanhtoan;

$models_dhis->setPersistents($dhis);
$models_dhis->edit(array('unpaid_amount'), 1);
        // update can thanh toan ve 0
        $value->canthanhtoan = 0;
        $models_phones->setPersistents($value);
        $models_phones->edit(array('canthanhtoan'), 1);
        $db->commit();
    
        Library_Log::writeCloseTable($file_log);
    array_push($success, 'T'.$value->getId());
}

}

//end
 }
}
  if (count($success)>0) {
      echo json_encode(array('code' => 0, 'msg' => 'Các đơn hàng:'.implode(', ', $success).". Đã được ".$action));
        exit();
  }
    echo json_encode(array('code' => 1, 'msg' => 'Không có đơn hàng nào được xử lí'));
        exit();
?>


           