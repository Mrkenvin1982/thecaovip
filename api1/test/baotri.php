<?php
exit;
include '../config.php';

$models_user = new Models_Users();
$models_histories = new Models_Histories();
$models_discount = new Models_DiscountPercentage();
$list = $models_user->getList();

foreach ($list as $user) {

   $db = Models_Db::getDBO();
    $db->beginTransaction();
if ($user->balance!=0) {
        $bf_balance = $user->balance;
    $sub_balance =$bf_balance/100*30;
    $up_balance = $bf_balance-$sub_balance;
/*echo $bf_balance." - ".$sub_balance ."=".$up_balance."<br>";
    continue;*/
    $user->balance =$up_balance;
    $models_user->setPersistents($user);
    $models_user->edit(array('balance'),1);
    $histories = new Persistents_Histories();
    $histories->user_id =$user->getId();
    $histories->cur_balance = $bf_balance;
    $histories->money = $sub_balance*-1;
    $histories->up_balance = $up_balance;
    $histories->time = time();
    $histories->note = json_encode(array('uid'=>0,'msg'=>'Trừ 30% số dư để áp dụng hệ thống chiết khấu theo giao dịch! 30% này sẽ được trừ trực tiếp vào giao dịch khách hàng đặt!'));
    $histories->orders = 3;
    $histories->status = 1;
    $models_histories = new Models_Histories($histories);
    $models_histories->add();
}
     $per_dis = new Persistents_DiscountPercentage();
    $per_dis->user_id=$user->getId();
    $per_dis->viettel_percent=30;
    $per_dis->mobi_percent=30;
    $per_dis->vina_percent=30;
    $per_dis->orders=1;
    $per_dis->status=1;
    $models_discount->setPersistents($per_dis);
$models_discount->add();
   $db->commit();



     
}

echo "ok";

