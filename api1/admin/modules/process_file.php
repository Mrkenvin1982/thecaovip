<?php
include 'config.php';
include "Classes/PHPExcel.php";
$users = $_SESSION['admin_logged'];

if(!is_object($users)) {
    // chuyen huong login
    header('location:login.php');
    exit();
}
exit;

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

    for ($row = 2; $row <= $lastRow; $row++) {
        $phone = $worksheet->getCell('A'.$row)->getValue();
        if(empty($phone)) {
            continue;
        }
        $type = intval($worksheet->getCell('B'.$row)->getValue());
        $canthanhtoan = $worksheet->getCell('C'.$row)->getValue();

        // check phone
        if(!Library_Validation::isPhoneNumber($phone) && $type == 0) {
            $msg_err[] = "SDT {$phone} không hợp lệ!";
            $err = true;
            break;
        }

        if(strpos($phone, "-") !== FALSE) {
            $msg_err[] = "FTTH {$phone} có dấu '-' không được thanh toán, vui lòng loại bỏ!";
            $err = true;
            break;
        }

        $sum += $canthanhtoan;
    }

    // check tien
    if($users->balance < $sum && !$err) {
        $msg_err[] = "Số dư phải lớn hơn " . number_format($sum);
        $err = true;
    }

    // sau khi da check ok lap lai lan nua
    if(!$err) {
        for ($row = 2; $row <= $lastRow; $row++) {
            $phone = $worksheet->getCell('A'.$row)->getValue();
            if(empty($phone)) {
                continue;
            }
            $type = intval($worksheet->getCell('B'.$row)->getValue());
            $canthanhtoan = intval($worksheet->getCell('C'.$row)->getValue());
            $join = intval($worksheet->getCell('D'.$row)->getValue());

            $phones = new Persistents_Phones();
            $phones->phone = strtolower(trim($phone));
            $phones->loai = 1;
            $phones->type = $type;
            $phones->canthanhtoan = $canthanhtoan;
            $phones->userid = $users->getId();
            $phones->gop = $join;
            $phones->time = time();
            $phones->status = 1;

            $models_phones = new Models_Phones($phones);
            $last_id = $models_phones->add(true);
            if(!$last_id) {
                $msg_err[] = $phone . " Không thể thêm vào hệ thống!";
            }
            else {
                $msg_suc[] = $phone . " đã thêm thành công, số tiền cần thanh toán : " . number_format($canthanhtoan);
                $db = Models_Db::getDBO();
                $db->beginTransaction();
                
                $models_users = new Models_Users();
                $users = $models_users->getObject($users->getId(), 1);
                $current_balance = $users->balance;
                $update_balance = $current_balance - $canthanhtoan;
                $users->balance = $update_balance;
                $models_users->setPersistents($users);
                
                $models_users->edit(array('balance'), 1);
                $_SESSION['admin_logged'] = $users;
                $db->commit();
                
                $phones = $models_phones->getObject($last_id);
                $phones->last_balance = $update_balance;
                $models_phones->setPersistents($phones);
                $models_phones->edit(array('last_balance'), 1);
                
                // them vao lich su cong tien
                $histories = new Persistents_Histories();
                $histories->admin_id = $users->getId();
                $histories->user_add = $users->getId();
                $histories->cur_balance = $current_balance;
                $histories->up_balance = $update_balance;
                $histories->money = $canthanhtoan*-1;
                $histories->time = time();
                $histories->note = 'Phone id : ' . $last_id;
                $histories->status = 1;
                $models_histories = new Models_Histories($histories);
                $models_histories->add();
            }
        }
    }
}
else {
    $msg_err = 'Post fail';
}

$_SESSION['msgerr'] = $msg_err;
$_SESSION['msgsuc'] = $msg_suc;
print_r($msg_err);
echo '</br>';
print_r($msg_suc);
exit;
header('location:index.php');
