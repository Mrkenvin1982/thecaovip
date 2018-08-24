<?php
include '../config.php';

// get post datas
foreach($_GET as $key => $value) {
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

if($end_date != '' && $start_date != '') {
    $arr_start_date = explode('/', $start_date);
    $arr_end_date = explode('/', $end_date);
    $start_limit = mktime(0, 0, 0, $arr_start_date[1], $arr_start_date[0], $arr_start_date[2]);
    $end_limit = mktime(23, 59, 59, $arr_end_date[1], $arr_end_date[0], $arr_end_date[2]);
}
else {
    $start_limit = mktime(0, 0, 0, date('m'), 1, date('Y'));
    $end_limit = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
}

$models_phones = new Models_Phones();
$status = intval($status);
if ($adminuser->group_id==1) {
    $qz='';
}else
{
 $qz=   "userid = {$adminuser->getId()} AND";

}

if($status == 0) {
    $list = $models_phones->customQuery("SELECT * FROM Phones WHERE ".$qz." time BETWEEN $start_limit AND $end_limit ORDER BY id DESC");
}
else if($status == 1) {
    $list = $models_phones->customQuery("SELECT * FROM Phones WHERE ".$qz." canthanhtoan <= 0 AND time BETWEEN $start_limit AND $end_limit ORDER BY id DESC");
}
else {
    $list = $models_phones->customQuery("SELECT * FROM Phones WHERE ".$qz." status = $status AND time BETWEEN $start_limit AND $end_limit ORDER BY id DESC");
}
?>

<table class="table table-bordered table-hover">
    <tr>
        <td>#</td>
        <td>ID</td>
        <td>Phone</td>
        <td>Loại thẻ</td>
        <td>HTTT</td>
        <td>Cần thanh toán</td>
        <td>Đã thanh toán</td>
        <td>Nạp gộp</td>
        <td>Trạng thái</td>
        <td></td>
    </tr>
    <?php
        $stt = 1;
        foreach ($list as $obj) {
            $tong += $obj->dathanhtoan;
            if($obj->canthanhtoan < 0) {
                $am += $obj->canthanhtoan;
            }
            else {
                $tctt += $obj->canthanhtoan;
            }
    ?>  
    <tr>
        <td class="text-"><?= $stt++ ?></td>
        <td class="text-"><?= $obj->getId() ?></td>
        <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>"><?= $obj->phone . "<br/>(Tạo lúc : " . date('d/m/Y - H:i:s', $obj->time) . ")" ?></td>
        <td>
            <?php 
                if($obj->loai == 1) {
                    echo '<img src="./images/vtt.png" width="50" />';
                }
                else {
                    echo '<img src="./images/vnp.png" width="50"/>';
                }
            ?>
        </td>
        <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>">
                <?php
                    switch($obj->type) {
                        case 0 : echo 'Trả Sau';
                            break;
                        case 1 : echo 'Trả trước';
                            break;
                        case 2 : echo 'FTTH Viettel';
                            break;
                        case 3 : echo 'Home phone';
                            break;
                    }
                ?>
        </td>
        <td><span class="text-danger" id="cantt_<?= $obj->phone ?>"><?= number_format($obj->canthanhtoan) ?><span></td>
        <td><span class="text-success" id="datt_<?= $obj->phone ?>"><?= number_format($obj->dathanhtoan) ?></span></td>
        <td>
            <?= $obj->gop == 1 ? '<span class="text-info">Có</span>' : '<span class="text-warning">Không</span>' ?>
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
                        echo "<span class='text-danger' id='status_" . $obj->phone . "'>SDT không thể xử lý, kiểm tra lại!</span>";
                    }
                    elseif($obj->status == 4){
                        echo "<span class='text-danger' id='status_" . $obj->phone . "'>Bị khoá!</span>";
                    }
                    elseif($obj->status == 5){
                        echo "<span class='text-danger' id='status_" . $obj->phone . "'>Đã xoá!</span>";
                    }
                }
            ?>
        </td>
        <td class="">
            <?php
                if($obj->canthanhtoan > 0) {
                    if($obj->status == 1) {
                        echo "<button class='btn btn-warning btn-xs' onclick='pausePhone(" . $obj->getId() . ")'>Dừng TT</button>&nbsp;";
                    }
                    elseif($obj->status == 0 || $obj->status == 3) {
                        echo "<button class='btn btn-success btn-xs' onclick='startPhone(" . $obj->getId() . ")'>Bật TT</button>&nbsp;";
                        echo "<button class='btn btn-success btn-xs' onclick='refund(" . $obj->getId() . ")'>Hoàn tiền</button>";
                    }
                }
            ?>
            <button class="btn btn-default btn-xs" onclick="detail(<?= $obj->getId() ?>)">Chi tiết</button>
        </td>
    </tr>
    <?php
        }
    ?>
    <tr>
        <td colspan="5">Tổng thanh toán</td>
        <td class="text-danger"><?= number_format($tctt) . "(" . number_format($am) . ")" ?></td>
        <td class="text-success"><?= number_format($tong) ?></td>
        <td colspan="4"></td>
    </tr>
</table>