<?php
include '../config.php';
if (!isset($_POST['data']) || count($_POST['data']) == 0) {
    echo json_encode(array('code' => 1, 'msg' => 'Không có dữ liệu'));
    exit();
} else {
    $data = $_POST['data'];
    $pass = $_POST['password'];
}
if (!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
$models_user         = new Models_Users();
$models_phones       = new Models_Phones();
$models_histories    = new Models_Histories();
$models_discount     = new Models_DiscountPercentage();
$models_default      = new Models_DefaultDiscount();
$models_prepaid      = new Models_PrepaidDiscount();
$models_hdis         = new Models_DiscountHistories();
$models_minimum      = new Models_MinimumMoney();
$hotSaleObject       = $models_prepaid->getObjectByCondition('', array('orders' => 4));
$minimum_ts_object   = $models_minimum->getObjectByCondition('', array('orders' => 0));
$minimum_ts          = $minimum_ts_object->price;
$minimum_tt_object   = $models_minimum->getObjectByCondition('', array('orders' => 1));
$minimum_tt          = $minimum_tt_object->price;
$minimum_ftth_object = $models_minimum->getObjectByCondition('', array('orders' => 2));
$minimum_ftth        = $minimum_ftth_object->price;
$minimum_gop_object  = $models_minimum->getObjectByCondition('', array('orders' => 4));
$minimum_gop         = $minimum_gop_object->price;

$models_one_mil  = new Models_OneMilTransactionAccess();
$one_mil_obj     = $models_one_mil->getObjectByCondition('', array('orders' => 1));
$one_mil         = $one_mil_obj->status;
$on_prepaid_obj  = $models_one_mil->getObjectByCondition('', array('orders' => 3));
$on_prepaid      = $on_prepaid_obj->status;
$on_ftth_obj     = $models_one_mil->getObjectByCondition('', array('orders' => 4));
$on_ftth         = $on_ftth_obj->status;
$on_postpaid_obj = $models_one_mil->getObjectByCondition('', array('orders' => 5));
$on_postpaid     = $on_postpaid_obj->status;
$haft_obj        = $models_one_mil->getObjectByCondition('', array('orders' => 6));
$haft            = $haft_obj->status;
$accessCard300kObject  = $models_one_mil->getObject(7);
$accessCard300k  = $accessCard300kObject->status;


$total_price = 0;
$user        = $models_user->getObjectByCondition('', array('id' => $adminuser->getId(), 'trans_pass' => md5($pass)));
if (!is_object($user)) {
    echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu giao dịch không chính xác!'));
    exit();
}
$numof_vt       = array('086', '096', '097', '098', '0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169');
$numof_mb       = array('090', '093', '0120', '0121', '0122', '0126', '0128', '089');
$numof_vn       = array('091', '094', '0123', '0124', '0125', '0127', '0129', '088');
$user_discount  = $models_discount->getObjectByCondition('', array('user_id' => $user->getId()));
$array_discount = array();
if (is_object($user_discount)) {
    $array_discount[1] = $user_discount->viettel_percent;
    $array_discount[2] = $user_discount->mobi_percent;
    $array_discount[3] = $user_discount->vina_percent;
    $array_discount[4] = $user_discount->ftth_percent;

} else {
    $default_discount = $models_default->getLastObject()->discount;

    $array_discount[1] = $array_discount[2] = $array_discount[3] = $array_discount[4] = $default_discount;

}
foreach ($data as $index => $value) {
    foreach ($value as $var => $val) {
        $$var = trim($val);
        if (!Library_Validation::antiSql($val)) {
            echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
            exit();
        }

    }

    if (!Library_Validation::isPhoneNumber($phone) && $type != 2) {
        echo json_encode(array('code' => 1, 'msg' => 'SDT dòng thứ ' . ($index + 1) . ' không hợp lệ!'));
        exit();
    }
     if ($type == 2&&strpos( $phone,'-')!=false) {
        echo json_encode(array('code' => 1, 'msg' => 'FTTH dòng thứ ' . ($index + 1) . ' không hợp lệ!'));
        exit();
    }
    $phone = strtolower($phone);
    $first = '';
    if (substr($phone, 0, 2) == '09' || substr($phone, 0, 2) == '08') {
        $first = substr($phone, 0, 3);
    } else {
        $first = substr($phone, 0, 4);

    }
    if (in_array($first, $numof_vt)) {
        $provider = 1;
    } elseif (in_array($first, $numof_mb)) {
        $provider = 2;
    } elseif (in_array($first, $numof_vn)) {
        $provider = 3;
    } elseif ($type == 2) {
        $provider = 4;
    } else {
        echo json_encode(array('code' => 1, 'msg' => 'SDT dòng thứ ' . ($index + 1) . ' không hợp lệ!'));
        exit();
    }
    $real_discount = $array_discount[$provider];

    $prepaid_discount = 0;
    if ($type == 1) {
        $prepaid = $models_prepaid->getObjectByCondition('', array('orders' => $provider));
        if (is_object($prepaid)) {
            $prepaid_discount = $prepaid->discount;
        }
    }
    $discount_percent = $real_discount - $prepaid_discount;
    $discount_percent += $hotSaleObject->discount;
    $discount_money = $price / 100 * $discount_percent;
    $real_price     = $price - $discount_money;
    $total_price += $real_price;

    if (!in_array($type, array(0, 1, 2)) || !in_array($join, array(0, 1))) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu dòng thứ ' . ($index + 1) . ' không hợp lệ!'));
        exit();
    }
    if ($type == 2 && $on_ftth == 0) {
        echo json_encode(array('code' => 1, 'msg' => 'FTTH đang tạm khóa, dữ liệu dòng thứ ' . ($index + 1) . ' không hợp lệ!'));
        exit();
    }
//ĐOẠN NÀY SẼ COMMENT SAU KHI CÓ THỂ HỖ TRỢ LOẠI THẺ KHÁC NGOÀI VIETTEL
    if (!in_array($provider, array(1, 4))) {
        echo json_encode(array('code' => 1, 'msg' => 'Hiện tại chỉ hỗ trợ thẻ viettel, SĐT dòng thứ ' . ($index + 1) . ' không hợp lệ!'));
        exit();
    }
    if ($type == 1 && $on_prepaid == 0) {
        echo json_encode(array('code' => 1, 'msg' => 'Hiện tại chúng tôi chưa nhận sim trả trước, SĐT dòng thứ ' . ($index + 1) . ' không hợp lệ!'));
        exit();
    }
    if ($type == 0 && $on_postpaid == 0) {
        echo json_encode(array('code' => 1, 'msg' => 'Hiện tại chúng tôi chưa nhận sim trả sau, SĐT dòng thứ ' . ($index + 1) . ' không hợp lệ!'));
        exit();
    }
//ĐẾN ĐÂY
    if ($type == 0 && ($price < $minimum_ts || $price % $minimum_ts != 0)) {
        echo json_encode(array('code' => 1, 'msg' => 'Số tiền cần thanh toán trả sau dòng thứ ' . ($index + 1) . ' phải là bội số của ' . number_format($minimum_ts)));
        exit();
    }
    if ($type == 1 && ($price < $minimum_tt || $price % $minimum_tt != 0)) {
        echo json_encode(array('code' => 1, 'msg' => 'Số tiền cần thanh toán trả trước dòng thứ ' . ($index + 1) . ' phải là bội số của ' . number_format($minimum_tt)));
        exit();
    }
    if ($type == 2 && ($price < $minimum_ftth || $price % $minimum_ftth != 0)) {
        echo json_encode(array('code' => 1, 'msg' => 'Số tiền cần thanh toán ftth dòng thứ ' . ($index + 1) . ' phải là bội số của ' . number_format($minimum_ftth)));
        exit();
    }
    if ($join == 0 && $price < $minimum_gop) {
        echo json_encode(array('code' => 1, 'msg' => "Thanh toán dưới " . number_format($minimum_gop) . " không thể chọn nạp không gộp, đơn hàng dòng thứ " . ($index + 1) . ' không hợp lệ '));
        exit();
    }

    if ($join == 0 && ((($price < 1000000 && !in_array($price, array(10000, 20000, 50000, 100000, 200000, 300000, 500000))) || $price > 1000000) || ($price == 1000000 && $one_mil == 0) || ($price == 500000 && $haft == 0)|| ($price == 300000 && $accessCard300k == 0))) {
        echo json_encode(array('code' => 1, 'msg' => "SĐT dòng thứ " . ($index + 1) . " không thể chọn không nạp gộp!"));
        exit();
    }

// kiem tra so du
    if ($user->balance < $real_price) {
        echo json_encode(array('code' => 1, 'msg' => 'Tài khoản bạn không đủ tiền!'));
        exit();
    }
}
if ($user->balance < $total_price) {
    echo json_encode(array('code' => 1, 'msg' => 'Tài khoản bạn không đủ tiền!'));
    exit();
}

$db = Models_Db::getDBO();
$db->beginTransaction();
foreach ($data as $index => $value) {
    foreach ($value as $var => $val) {
        $$var = trim($val);
    }
    $phone         = strtolower($phone);
    $real_discount = $array_discount[$provider];

    $prepaid_discount = 0;
    if ($type == 1) {
        $prepaid = $models_prepaid->getObjectByCondition('', array('orders' => $provider));
        if (is_object($prepaid)) {
            $prepaid_discount = $prepaid->discount;
        }
    }
    $real_provider = $provider;
    if ($provider == 4) {
        $real_provider = 1;
    }
    $discount_percent = $real_discount - $prepaid_discount;
    $discount_percent += $hotSaleObject->discount;
    $discount_money       = $price / 100 * $discount_percent;
    $real_price           = $price - $discount_money;
    $phones               = new Persistents_Phones();
    $phones->phone        = $phone;
    $phones->loai         = $real_provider;
    $phones->type         = $type;
    $phones->canthanhtoan = $price;
    $phones->gop          = $join;
    $phones->userid       = $user->getId();
    $phones->time         = time();
    $phones->status       = 1;

    $models_phones->setPersistents($phones);
    $last_id = $models_phones->add(1);
    if ($last_id) {

        $users           = $models_user->getObject($adminuser->getId(), 1);
        $current_balance = $users->balance;
        $update_balance  = $current_balance - $real_price;
        $users->balance  = $update_balance;
        $models_user->setPersistents($users);

        $models_user->edit(array('balance'), 1);

        $phones               = $models_phones->getObject($last_id);
        $phones->last_balance = $update_balance;
        $models_phones->setPersistents($phones);
        $models_phones->edit(array('last_balance'), 1);

        // them vao lich su cong tien
        $histories              = new Persistents_Histories();
        $histories->user_id     = $users->getId();
        $histories->cur_balance = $current_balance;
        $histories->money       = $real_price * -1;
        $histories->up_balance  = $update_balance;
        $histories->time        = time();
        $histories->note        = json_encode(array('uid' => $users->getId(), 'msg' => "Thêm thanh toán " . number_format($price) . " SĐT: " . $phone));
        $histories->orders      = 2;
        $histories->status      = 1;

        $models_histories->setPersistents($histories);
        $models_histories->add();
        $per_hdis                   = new Persistents_DiscountHistories();
        $per_hdis->user_id          = $users->getId();
        $per_hdis->phone_id         = $phones->getId();
        $per_hdis->money            = $price;
        $per_hdis->real_discount    = $real_discount;
        $per_hdis->discount_percent = $discount_percent;
        $per_hdis->discount_money   = $discount_money;
        $per_hdis->real_money       = $real_price;
        $per_hdis->trans_type       = $type;
        $per_hdis->orders           = 1;
        $per_hdis->status           = 1;
        $models_hdis->setPersistents($per_hdis);
        $models_hdis->add();

    }

}
$db->commit();

echo json_encode(array('code' => 0, 'msg' => 'Thêm đơn hàng thành công'));
exit;
