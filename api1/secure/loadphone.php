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
    
    if($end_date != '' && $start_date != '') {
        $arr_start_date = explode('/', $start_date);
        $arr_end_date = explode('/', $end_date);
        $start_limit = mktime(0, 0, 0, $arr_start_date[1], $arr_start_date[0], $arr_start_date[2]);
        $end_limit = mktime(23, 59, 59, $arr_end_date[1], $arr_end_date[0], $arr_end_date[2]);
    }
    else {
        $start_limit = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $end_limit = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
    }

    $models_users = new Models_Users();
    $models_phones = new Models_Phones();

    $status = intval($status);    
    if($user_id == 0) {
        $list = $models_phones->customQuery("SELECT * FROM Phones WHERE time BETWEEN $start_limit AND $end_limit AND status = {$status} ORDER BY id DESC");
    }
    else {
        $list = $models_phones->customQuery("SELECT * FROM Phones WHERE userid = {$user_id} AND time BETWEEN $start_limit AND $end_limit AND status = {$status} ORDER BY id DESC");
    }
?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <td>#</td>
            <td>ID</td>
            <td>Phone</td>
            <td>Loại</td>
            <td>HTTT</td>
            <td>Cần thanh toán</td>
            <td>Đã thanh toán</td>
            <td>Trạng thái</td>
            <td>Chi tiết xử lý!</td>
            <td>Nạp gộp</td>
            <td>Last Balance</td>
            <td>Status</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php
            $stt = 1;
            foreach ($list as $obj) {
                $tong += $obj->dathanhtoan;
                if($obj->canthanhtoan >= 0 && $obj->status == 1) {
                    $tong_ctt += $obj->canthanhtoan;
                }
                elseif($obj->canthanhtoan < 0) {
                    $tong_am += $obj->canthanhtoan;
                }
        ?>  
        <tr>
            <td><?= $stt++ ?></td>
            <td><?= $obj->getId()?></td>
            <td class="<?= $obj->type == 0 ? 'text-primary' : 'text-warning' ?>">
                <?= $obj->phone . "<br/>(Tạo lúc : " . date('d/m/Y - H:i:s', $obj->time) . ")" ?>
                <br/>
                <?= 'User : ' . $models_users->getObject($obj->userid)->name  ?>
            </td>
            <td>
                <?php 
                    if($obj->loai == 1) {
                        echo '<img src="../images/vtt.png" width="50" />';
                    }
                    else {
                        echo '<img src="../images/vnp.png" width="50"/>';
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
                        case 3 : echo 'HomePhone Viettel';
                            break;
                    }
                ?>
            </td>
            <td><span id="cantt_<?= $obj->phone ?>"><?= number_format($obj->canthanhtoan) ?><span></td>
            <td><span id="datt_<?= $obj->phone ?>"><?= number_format($obj->dathanhtoan) ?></span></td>
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
            <td>
                <div id="<?= $obj->phone ?>" style="max-width:300px; height: 100px; overflow: scroll"></div>
            </td>
            <td><?= $obj->gop == 1 ? 'Có' : 'Không' ?></td>
            <td><?= number_format($obj->last_balance) ?> VND</td>
            <td><?= $obj->status ?></td>
            <td class="">
                <?php
                    if($obj->canthanhtoan > 0) {
                        if($obj->status == 1) {
                            echo "<button class='btn btn-warning btn-xs pause' data='{$obj->getId()}'>Dừng TT</button>&nbsp;";
                        }
                        elseif($obj->status == 0 || $obj->status == 3 || $obj->status == 4 || $obj->status == 5) {
                            echo "<button class='btn btn-danger btn-xs del' data='{$obj->getId()}'>Xoá</button>&nbsp;";
                            echo "<button class='btn btn-success btn-xs start' data='{$obj->getId()}'>Bật TT</button>&nbsp;";
                        }
                        elseif($obj->status == 2) {
                            echo "<button class='btn btn-success btn-xs start' data='{$obj->getId()}'>Bật TT</button>&nbsp;";
                        }
                    }
                    else {
                        echo "<button class='btn btn-danger btn-xs del' data='{$obj->getId()}'>Xoá</button>";
                    }
                ?>
                <a class="btn btn-default btn-xs" target="_blank" href="detail.php?phone_id=<?= $obj->getId() ?>">Chi tiết</a>
            </td>
        </tr>
        <?php
            }
        ?>
    </tbody>
    <tr>
        <td colspan="5">Tổng thanh toán</td>
        <td><?= number_format($tong_ctt) ?> (<?= number_format($tong_am) ?>)</td>
        <td class="text-success"><?= number_format($tong) ?></td>
        <td><?= number_format($tong * 0.87) ?></td>
        <td colspan="5"></td>
    </tr>
</table>
