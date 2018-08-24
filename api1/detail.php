<?php
include 'config.php';
$users = $_SESSION['admin_logged'];

if(!is_object($users)) {
    // chuyen huong login
    header('location:login.php');
    exit();
}

$phone_id = $_GET['phone_id'];
$models_phones = new Models_Phones();
$phones = $models_phones->getObjectByCondition('', array('id' => $phone_id, 'userid' => $users->getId()));
if(is_object($phones)) {
    $models_cards = new Models_Cards();
    $list = $models_cards->customFilter('', array('phone_id' => $phone_id, ''));
}
else {
    header('location:index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>

    <script type="text/javascript" src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="./node_modules/jquery-form/jquery.form.js"></script>
    <script type="text/javascript" src="./node_modules/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h3>Danh sách mã thẻ đã thanh toán <small><?= $users->name ?> ( <?= date('m/Y') ?> )</small></h3>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <td class="col-fixed-10">STT</td>
                        <td>Phone</td>
                        <td>Kiểu</td>
                        <td>Pin</td>
                        <td>Seri</td>
                        <td>Mệnh giá</td>
                        <td>Thanh toán lúc</td>
                        <td>Trạng thái</td>
                    </tr>
                    <?php
                        $stt = 1;
                        foreach ($list as $obj) {
                            $tong += $obj->price;
                    ?>  
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>"><?= $phones->phone . "<br/>(Tạo lúc : " . date('d/m/Y - H:i:s', $phones->time) . ")" ?></td>
                        <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>"><?= $phones->type == 0 ? 'Tra sau' : 'Tra truoc' ?></td>
                        <td><?= $obj->pin ?></td>
                        <td><?= $obj->seri ?></td>
                        <td><?= number_format($obj->price) ?></td>
                        <td><?= date('d/m/Y - H:i:s', $obj->time) ?></td>
                        <td><?= "<span class='text-success'>Hoàn thành</span>"; ?></td>
                    </tr>
                    <?php
                        }
                    ?>
                    <tr>
                        <td colspan="5">Tổng thanh toán</td>
                        <td class="text-success"><?= number_format($tong) ?></td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <button type="button" onclick="history.back()" class="btn btn-success pull-right">Quay lại</button>
            </div>
        </div>
    </div>
</body>
</html>