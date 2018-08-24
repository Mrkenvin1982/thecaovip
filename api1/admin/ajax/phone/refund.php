<?php
include '../../../config.php'; 

// get post data
foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}

if(!is_object($admin_logged)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}

$id = intval($id);

$models_phones = new Models_Phones();
$phones = $models_phones->getObject($id);
if(is_object($phones)) {
    if((($phones->status == 0 || $phones->status == 3) && $phones->canthanhtoan > 0)) {
        $money_ref = $phones->canthanhtoan;
        
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
        $db->commit();

        // them vao lich su cong tien

        $histories = new Persistents_Histories();
               $histories->user_id =$users->getId();
    $histories->cur_balance = $current_balance;
    $histories->up_balance = $update_balance;
    $histories->time = time();
    $histories->note = json_encode(array('uid'=>$users->getId(),'msg'=>'Back tiền từ gd: T'.$phones->getId()));
    $histories->orders = 1;
    $histories->status = 1;
     
        $models_histories = new Models_Histories($histories);
        $models_histories->add();
        
        // update can thanh toan ve 0
        $phones->canthanhtoan = 0;
        $models_phones->setPersistents($phones);
        $models_phones->edit(array('canthanhtoan'), 1);
    
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