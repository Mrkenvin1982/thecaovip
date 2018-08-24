<?php
include 'config.php';
include "Classes/PHPExcel.php";

$user = $_SESSION['admin_logged'];
$models_user = new Models_Users();
$models_phones = new Models_Phones();
$models_histories = new Models_Histories();
$models_discount = new Models_DiscountPercentage();
$models_default = new Models_DefaultDiscount();
$models_prepaid = new Models_PrepaidDiscount();
$models_hdis = new Models_DiscountHistories();
$models_minimum = new Models_MinimumMoney();
$hotSaleObject = $models_prepaid->getObjectByCondition('',array('orders'=>4));
$minimum_ts_object = $models_minimum->getObjectByCondition('',array('orders'=>0)); 
$minimum_ts =$minimum_ts_object->price;
$minimum_tt_object = $models_minimum->getObjectByCondition('',array('orders'=>1)); 
$minimum_tt =$minimum_tt_object->price;
$minimum_ftth_object = $models_minimum->getObjectByCondition('',array('orders'=>2)); 
$minimum_ftth =$minimum_ftth_object->price;
$minimum_gop_object = $models_minimum->getObjectByCondition('',array('orders'=>4));  
$minimum_gop =$minimum_gop_object->price;
$models_one_mil = new Models_OneMilTransactionAccess();
$one_mil_obj = $models_one_mil->getObjectByCondition('',array('orders'=>1));
$one_mil = $one_mil_obj->status;
$on_prepaid_obj = $models_one_mil->getObjectByCondition('',array('orders'=>3));
$on_prepaid = $on_prepaid_obj->status;
$on_ftth_obj = $models_one_mil->getObjectByCondition('',array('orders'=>4));
$on_ftth = $on_ftth_obj->status;
$on_postpaid_obj = $models_one_mil->getObjectByCondition('',array('orders'=>5));
$on_postpaid = $on_postpaid_obj->status;
$haft_obj = $models_one_mil->getObjectByCondition('',array('orders'=>6));
$haft = $haft_obj->status;
$users = $models_user->getObject($user->getId());
if(!is_object($users)) {
    // chuyen huong login
    header('location:login.php');
    exit();
}
  $numof_vt =array('086', '096', '097', '098', '0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169');
  $numof_mb =array('090', '093', '0120', '0121', '0122', '0126', '0128', '089');
  $numof_vn =array('091', '094', '0123', '0124', '0125', '0127', '0129','088');
if(isset($_POST["submit"])) {
    $msg_err = [];
    $msg_suc = [];

    $tmpfname = $_FILES["fileToUpload"]["tmp_name"];
    $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
    $excelObj = $excelReader->load($tmpfname);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $err = false;
    $sum = 0;
$user_discount = $models_discount->getObjectByCondition('',array('user_id'=>$users->getId()));
$array_discount=array();
if (is_object($user_discount)) {
    $array_discount[1] = $user_discount->viettel_percent;
    $array_discount[2] = $user_discount->mobi_percent;
    $array_discount[3] = $user_discount->vina_percent;
    $array_discount[4] = $user_discount->ftth_percent;

}else{
$default_discount = $models_default->getLastObject()->discount;

 $array_discount[1] = $array_discount[2]=$array_discount[3]=$array_discount[4] =$default_discount;

}
    for ($row = 2; $row <= $lastRow; $row++) {
        $phone = strtolower($worksheet->getCell('A'.$row)->getValue());
        if(empty($phone)) {
            continue;
        }


        $loai = intval($worksheet->getCell('B'.$row)->getValue());
        $type = intval($worksheet->getCell('C'.$row)->getValue());
        $canthanhtoan = intval($worksheet->getCell('D'.$row)->getValue());
        $join = intval($worksheet->getCell('E'.$row)->getValue());
        // check phone
        $first ='';
if (strlen($phone)==10&&(substr($phone,0, 2)=='09'||substr($phone,0, 2)=='08')) {
    $first = substr($phone,0, 3);
}elseif(strlen($phone)==11){
    $first = substr($phone,0, 4);
}
        if (!in_array($first, $numof_vt)&&$type!=2) {
   $msg_err[] = "SDT {$phone} không hợp lệ!";
            $err = true;
            break;
}
        if(!in_array($type, array(0, 1,2))) {
            $msg_err[] = "SDT {$phone} không hợp lệ!";
            $err = true;
            break;
        }
        if ((strpos($phone, '_')!=false)&&$type!=2) {
             $msg_err[] = "SDT {$phone} là FTTH phải chọn loại thuê bao là 2 chứ không phải $type !";
            $err = true;
            break; 
        }
        //check loai
     if(!in_array($loai, array(1,2,3))) {
            $msg_err[] = "{$phone} nhà mạng không hợp lệ!";
            $err = true;
            break;
        }
           if($loai!=1) {
            $msg_err[] = "Hiện nay mới chỉ hỗ trợ thẻ viettel {$phone} không hợp lệ!";
            $err = true;
            break;
        }

if ($join==0&&((($canthanhtoan<1000000&&!in_array($canthanhtoan, array(10000,20000,50000,100000,200000,300000,500000)))||$canthanhtoan>1000000)||($canthanhtoan==1000000&&$one_mil==0)||($canthanhtoan==500000&&$haft==0))) {
      $msg_err[] = "SĐT {$phone} không thể chọn không nạp gộp!";
            $err = true;
            break;
}
if($join==0&&$canthanhtoan < $minimum_gop) {

     $msg_err[] = "Thanh toán dưới ".number_format($minimum_gop)." không thể chọn nạp không gộp, SĐT {$phone} không hợp lệ!";
            $err = true;
            break;
}
if ($type==1&&$on_prepaid==0) {
       $msg_err[] = "Hiện tại chúng tôi chưa nhận sim trả trước, SĐT {$phone} không hợp lệ!";
            $err = true;
            break;

}
if($type==0&&($canthanhtoan < $minimum_ts||$canthanhtoan%$minimum_ts!=0)) {
	  $msg_err[] = "Số tiền cần thanh toán trả sau của số điện thoại {$phone} phải là bội số của ".number_format($minimum_ts);
            $err = true;
            break;
 
}
if($type==1&&($canthanhtoan < $minimum_tt||$canthanhtoan%$minimum_tt!=0)) {
	  $msg_err[] = "Số tiền cần thanh toán trả trước của số điện thoại {$phone} phải là bội số của ".number_format($minimum_tt);
            $err = true;
            break;
   
}
if($type==2&&($canthanhtoan < $minimum_ftth||$canthanhtoan%$minimum_ftth!=0)) {
	  $msg_err[] = "Số tiền cần thanh toán ftth {$phone} phải là bội số của ".number_format($minimum_ftth);
            $err = true;
            break;
}
if ($type==0&&$on_postpaid==0) {
        $msg_err[] = "Hiện tại chúng tôi chưa nhận sim trả sau, SĐT {$phone} không hợp lệ!";
            $err = true;
            break;
}
        if ($type == 2&&$on_ftth==0) {
           $msg_err[] = "Hiện tại FTTH đang tạm khóa, {$phone} không hợp lệ!";
            $err = true;
            break;  
        }
$real_discount =$array_discount[$loai];
if ($type==2) {
    $real_discount =$array_discount[4];
}
$prepaid_discount=0;
    if ($type==1) {
$prepaid = $models_prepaid->getObjectByCondition('',array('orders'=>$loai));
if (is_object($prepaid)) {
     $prepaid_discount=$prepaid->discount;
}
    }
$discount_percent=$real_discount-$prepaid_discount; 
$discount_percent+=$hotSaleObject->discount; 
$discount_money=$canthanhtoan/100*$discount_percent;
$real_price = $canthanhtoan-$discount_money;
        $sum += $real_price;
    }

    // check tien
    if($users->balance < $sum && !$err) {
        $msg_err[] = "Số dư phải lớn hơn " . number_format($sum);
        $err = true;
    }



    // sau khi da check ok lap lai lan nua
    if(!$err) {

     $db = Models_Db::getDBO();
                $db->beginTransaction();
        for ($row = 2; $row <= $lastRow; $row++) {
            $phone = strtolower($worksheet->getCell('A'.$row)->getValue());
            if(empty($phone)) {
                continue;
            }

            $loai = intval($worksheet->getCell('B'.$row)->getValue());
            $type = intval($worksheet->getCell('C'.$row)->getValue());
            $canthanhtoan = intval($worksheet->getCell('D'.$row)->getValue());
            $join = intval($worksheet->getCell('E'.$row)->getValue());

$real_discount =$array_discount[$loai];
if ($type==2) {
    $real_discount =$array_discount[4];
}
$prepaid_discount=0;
    if ($type==1) {
$prepaid = $models_prepaid->getObjectByCondition('',array('orders'=>$loai));
if (is_object($prepaid)) {
     $prepaid_discount=$prepaid->discount;
}
    }
$discount_percent=$real_discount-$prepaid_discount; 
$discount_percent+=$hotSaleObject->discount; 

$discount_money=$canthanhtoan/100*$discount_percent;
$real_price = $canthanhtoan-$discount_money;


            $phones = new Persistents_Phones();
            $phones->phone = $phone;
            $phones->loai = $loai;
            $phones->type = $type;
            $phones->canthanhtoan = $canthanhtoan;
            $phones->userid = $users->getId();
            $phones->gop = $join;
            $phones->time = time();
            $phones->status = 1;
$models_phones->setPersistents($phones);
            $last_id = $models_phones->add(true);
            if(!$last_id) {
                $msg_err[] = $phone . " Không thể thêm vào hệ thống!";
            }
            else {
                $msg_suc[] = $phone . " đã thêm thành công, số tiền cần thanh toán : " . number_format($canthanhtoan);
           
                
                $users = $models_user->getObject($users->getId(), 1);
                $current_balance = $users->balance;
                $update_balance = $current_balance - $real_price;
                $users->balance = $update_balance;
                $models_user->setPersistents($users);
                
                $models_user->edit(array('balance'), 1);
                $_SESSION['admin_logged'] = $users;
                

                $phones = $models_phones->getObject($last_id);
                $phones->last_balance = $update_balance;
                $models_phones->setPersistents($phones);
                $models_phones->edit(array('last_balance'), 1);
                
                // them vao lich su cong tien
     $histories = new Persistents_Histories();
    $histories->user_id =$users->getId();
    $histories->cur_balance = $current_balance;
    $histories->money = $real_price*-1;
    $histories->up_balance = $update_balance;
    $histories->time = time();

    $histories->note = json_encode(array('uid'=>$users->getId(),'msg'=>"Thêm thanh toán ".number_format($canthanhtoan)." SĐT: ".$phone));
    $histories->orders = 2;
    $histories->status = 1;
    $models_histories->setPersistents($histories);
                $models_histories->add();

    $per_hdis = new Persistents_DiscountHistories();
    $per_hdis->user_id =$users->getId();
    $per_hdis->phone_id =$phones->getId();
    $per_hdis->money =$canthanhtoan;
    $per_hdis->real_discount =$real_discount;
    $per_hdis->discount_percent =$discount_percent;
    $per_hdis->discount_money =$discount_money;
    $per_hdis->real_money =$real_price;
    $per_hdis->trans_type =$type;
    $per_hdis->orders =1;
    $per_hdis->status =1;
    $models_hdis->setPersistents($per_hdis);
    $models_hdis->add();
            }
        }
                $db->commit();

    }

}
else {
    $msg_err[] = 'Post fail';
}

if (count($msg_err)>0) {
	$_SESSION['msgerr'] = $msg_err;
}
if (count($msg_suc)>0) {
	$_SESSION['msgsuc'] = $msg_suc;
}

/*print_r($msg_err);
echo '</br>';
print_r($msg_suc);
exit;*/
header('location:khop-the.php');
