<?php
include '../config.php';

// get post data
foreach ($_POST as $key => $value)
{
    $$key = trim($value);

    if (!Library_Validation::antiSql($value))
    {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}

if (!is_object($adminuser))
{
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}


// check name
if(strlen($name) <= 5) {
    echo json_encode(array('code' => 1, 'msg' => 'Tên tuổi dài dài 1 tý!'));
    exit();
}

// check phone
if(!Library_Validation::isPhoneNumber($userphone)) {
    echo json_encode(array('code' => 1, 'msg' => 'SDT không hợp lệ!'));
    exit();
}

if(strlen($password) <= 7) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu dài dài 1 tý!'));
    exit();
}
if(strlen($trans_pass) <= 7) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu giao dịch dài dài 1 tý!'));
    exit();
}
$models_gr = new Models_Groupz();
$models_discount = new Models_DiscountPercentage();
$models_default = new Models_DefaultDiscount();
$checkgr = $models_gr->getObject($group_id);
if (!is_object($checkgr)) {
    echo json_encode(array('code' => 1, 'msg' => 'Vui lòng chọn cấp đại lí!'));
    exit();
}
if ($group_id<=$adminuser->group_id) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn không đủ quyền!'));
    exit();
}

$viettel_percent =floatval($viettel_percent);
$mobi_percent =floatval($mobi_percent);
$vina_percent =floatval($vina_percent);
$ftth_percent =floatval($ftth_percent);

if ($adminuser->group_id!=1) {

        $fuser_discount = $models_discount->getObjectByCondition('',array('user_id'=>$adminuser->getId()));
        if (!is_object($fuser_discount)) {
        $default_discount = $models_default->getLastObject()->discount;
$fuser_viettel_dis =$fuser_mobi_dis=$fuser_vina_dis=$default_discount;
        }else{
            $fuser_viettel_dis = $fuser_discount->viettel_percent;
            $fuser_mobi_dis = $fuser_discount->mobi_percent;
            $fuser_vina_dis = $fuser_discount->vina_percent;
            $fuser_ftth_dis = $fuser_discount->ftth_percent;

        }

            if ($viettel_percent>$fuser_viettel_dis) {
                    echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ viettel của bạn là: ".$fuser_viettel_dis."% không thể set chiết khấu cao hơn của bạn!" ));
        exit();
            }
            if ($mobi_percent>$fuser_mobi_dis) {
                    echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ mobi của bạn là: ".$fuser_mobi_dis."% không thể set chiết khấu cao hơn của bạn!" ));
        exit();
            }
                            if ($vina_percent>$fuser_vina_dis) {
                    echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ vina của bạn là: ".$fuser_vina_dis."% không thể set chiết khấu cao hơn của bạn!" ));
        exit();
            }
                                       if ($ftth_percent>$fuser_ftth_dis) {
                    echo json_encode(array('code' => 1, 'msg' => "Chiết khấu ftth của bạn là: ".$fuser_ftth_dis."% không thể set chiết khấu cao hơn của bạn!" ));
        exit();
            }

}
// them vao data
$users = new Persistents_Users();
$users->name = $name;
$users->phone = $userphone;

$salt = md5(rand(0, 10000000000000));
$password = hash('sha256', $password . $salt);
$scret_key = md5(hash('sha256', $salt . md5(rand(0, 10000000000000).time())));

$users->refer = $adminuser->getId();
$users->salt = $salt;
$users->password = $password;
$users->trans_pass = md5($trans_pass);
$users->change_pass = 1;
$users->scret_key = $scret_key;
$users->group_id = $group_id;
$users->time = time();
$users->status = 1;

$models_users = new Models_Users($users);
$lasid =$models_users->add(1);
if($lasid) {
    $per_dis = new Persistents_DiscountPercentage();
    $per_dis->user_id=$lasid;
    $per_dis->viettel_percent=$viettel_percent;
    $per_dis->mobi_percent=$mobi_percent;
    $per_dis->vina_percent=$vina_percent;
    $per_dis->ftth_percent=$ftth_percent;

    $per_dis->orders=1;
    $per_dis->status=1;
    $models_discount->setPersistents($per_dis);
$models_discount->add();
    
    echo json_encode(array('code' => 0, 'msg' => 'Thành công!'));
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'Tài khoản đã tồn tại!'));
}
