<?php
include '../config.php';

// get post data
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

$id = intval($id);
$models_histories = new Models_Histories();
$models_phones = new Models_Phones();
$models_dhis = new Models_DiscountHistories();
$models_discount = new Models_DiscountPercentage();
$models_default = new Models_DefaultDiscount();
$phones = $models_phones->getObject($id);
if(is_object($phones)) {
    if((($phones->status == 0 || $phones->status == 3) && $phones->canthanhtoan > 0)) {
$dhis = $models_dhis->getObjectByCondition('',array('phone_id'=>$phones->getId()));
$money = $dhis->money;
$percent =100-$dhis->discount_percent;

$canthanhtoan =$phones->canthanhtoan;
$money_ref = $canthanhtoan/100*$percent;


        $file_log = "refund_money.log";
        Library_Log::writeOpenTable($file_log);
        Library_Log::writeHtml("Admin : " . $adminuser->getId(), $file_log);
        Library_Log::writeHtml("Refund for : {$phones->phone}, Money : {$money_ref}", $file_log);

        // cong tien 
        $db = Models_Db::getDBO();
        $db->beginTransaction();
        $users = $models_users->getObject($phones->userid, 1);
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
    $histories->note = json_encode(array('uid'=>$users->getId(),'msg'=>'Back tiền từ gd: T'.$phones->getId()));
    $histories->orders = 4;
    $histories->status = 1;
     
        $models_histories->setPersistents($histories);
        $models_histories->add();
        

        //nếu có hoa hồng
            if ($phones->dathanhtoan>0&&$users->refer!=0) {
            $fuser = $models_users->getObject($users->refer, 1);
if (is_object($fuser)) {
              
            $fdiscount = $models_discount->getObjectByCondition('',array('user_id'=>$fuser->getId()));
    if (is_object($fdiscount)) {
               switch ($phones->loai) {
               case 1:
                  $fdis = $fdiscount->viettel_percent;
                  if ($phones->type==2) {
                      $fdis = $fdiscount->ftth_percent;
                  }
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
    $fdiscount_money = $phones->dathanhtoan/100*$fpercent;
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
    $histories->note = json_encode(array('uid'=>$users->getId(),'msg'=>' Tiền hoa hồng từ gd: T'.$phones->getId()));
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
        $models_commis =new Models_CommissionHistories($commiss);
        $models_commis->add();

}
        }
$dhis->unpaid_amount=$canthanhtoan;
$models_dhis->setPersistents($dhis);
$models_dhis->edit(array('unpaid_amount'), 1);
        // update can thanh toan ve 0
        $phones->canthanhtoan = 0;
        $models_phones->setPersistents($phones);
        $models_phones->edit(array('canthanhtoan'), 1);
        $db->commit();
    
        Library_Log::writeCloseTable($file_log);
        echo json_encode(array('code' => 0, 'msg' => 'Cập nhật thành công!'));
    }
    else {
        echo json_encode(array('code' => 1, 'msg' =>  'Thao tác sai!'));
    }
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'SDT không tồn tại!'));
}