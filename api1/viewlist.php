<?php
include 'config.php';

if(!is_object($adminuser)) {
    // chuyen huong login
    header('location:login.php');
    exit();
}

$acc = base64_decode($_GET['acc']);
$userid = intval(Library_String::decryptstr($acc, $key_enc));
$users = $models_users->getObject($userid);
if(!is_object($users) || $users->status == 0) {
    exit('Khong ton tai acc nay!');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript" src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="./node_modules/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>Account : <small><?= $users->name ?> - <?= date('m/Y') ?> <h3 class="text-success"><?= number_format($users->balance)?> VND</h3></small></h1>
        </div>
        <div class="page-header">
            <h4>Danh sách số điện thoại cần thanh toán</h4>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>#</td>
                        <td>ID</td>
                        <td>Phone</td>
                        <td>Loại</td>
                        <td>Cần thanh toán</td>
                        <td>Đã thanh toán</td>
                        <td>Nạp gộp</td>
                        <td>Số dư cuối</td>
                        <td>Trạng thái</td>
                    </tr>
                    <?php
                        $models_phones = new Models_Phones();
                        $time = mktime(0, 0, 0, date('m'), 1, date('Y'));
                        $list = $models_phones->customFilter('', array('userid' => $users->getId(), 'time' => array($time, '>=')));
                        $stt = 1;
                        foreach ($list as $obj) {
                            $tong += $obj->dathanhtoan;
                    ?>  
                    <tr>
                        <td class="text-"><?= $stt++ ?></td>
                        <td class="text-"><?= $obj->getId() ?></td>
                        <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>"><?= $obj->phone . "<br/>(Tạo lúc : " . date('d/m/Y - H:i:s', $obj->time) . ")" ?></td>
                        <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>"><?= $obj->type == 0 ? 'Trả sau' : 'Trả trước' ?></td>
                        <td><span id="cantt_<?= $obj->phone ?>"><?= number_format($obj->canthanhtoan) ?><span></td>
                        <td><span id="datt_<?= $obj->phone ?>"><?= number_format($obj->dathanhtoan) ?></span></td>
                        <td>
                            <?= $obj->gop == 1 ? '<span class="text-info">Có</span>' : '<span class="text-warning">Không</span>' ?>
                        </td>
                        <td>
                            <?= number_format($obj->last_balance) ?> VND
                        </td>
                        <td>
                            <?php
                                if($obj->canthanhtoan <= 0){
                                    echo "<span class='text-success' id='status_" . $obj->phone . "'>Hoàn thành!</span>";
                                }
                                else {
                                    if($obj->status == 0){
                                        echo "<span class='text-danger' id='status_" . $obj->phone . "'>Không hoạt động</span>";
                                    }
                                    elseif($obj->status == 1){
                                        echo "<span class='text-warning' id='status_" . $obj->phone . "'>Chờ xử lý</span>";
                                    }
                                    elseif($obj->status == 2){
                                        echo "<span class='text-warning' id='status_" . $obj->phone . "'>Đang xử lý...</span>";
                                    }
                                    elseif($obj->status == 3){
                                        echo "<span class='text-danger' id='status_" . $obj->phone . "'>Hình thức thanh toán sai!</span>";
                                    }
                                    elseif($obj->status == 4){
                                        echo "<span class='text-danger' id='status_" . $obj->phone . "'>Bị khoá!</span>";
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                    <tr>
                        <td colspan="5">Tổng thanh toán</td>
                        <td class="text-success"><?= number_format($tong) ?></td>
                        <td colspan="3"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>