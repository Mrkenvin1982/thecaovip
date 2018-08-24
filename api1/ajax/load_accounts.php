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

$models_users = new Models_Users();
$models_histories = new Models_Histories();
$models_phones = new Models_Phones();
        if ($adminuser->group_id==1) {
            $listusers = $models_users->customFilter('', array('status'=>array(0,'!=')));
        }else{
$listusers = $models_users->customFilter('', array('status'=>array(0,'!='), 'refer' => $adminuser->getId()));

        }
?>

<table class="table table-hover">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Phone</th>
        <?php if ($adminuser->group_id==1): ?>
        <th>Cấp</th>
        <?php endif ?>
        <th>Số dư</th>
        <th>Tổng tiền</th>
        <th>Đã thanh toán</th>
        <th>Thực thu</th>
        <th>Tạo lúc</th>
        <th></th>
    </tr>
    <?php
        $time = mktime(0, 0, 0, $month, 1, date('Y'));
        $end_time = mktime(23, 59, 59, $month, cal_days_in_month(CAL_GREGORIAN, $month, date('Y')), date('Y'));
        $stt = 0;
        $total_du=0;
        $total = 0;
        $total_dathanhtoan =0;
        $total_thucthu=0;
        foreach ($listusers as $obj) {
            $stt++;
            $link = 'viewlist.php?acc=' . base64_encode(Library_String::encryptStr($obj->getId(), $key_enc));
            $total_cong = $models_histories->getSumByColumn2('money', "user_id = {$obj->getId()}  AND time BETWEEN {$time} AND {$end_time}");
            $total += $total_cong;
            $total_du += $obj->balance;

            $dathanhtoan = $models_phones->getSumByColumn2('dathanhtoan', "userid = {$obj->getId()} AND time BETWEEN {$time} AND {$end_time}");
            $tienam = $models_phones->getSumByColumn2('canthanhtoan', "userid = {$obj->getId()} AND canthanhtoan < 0 AND time BETWEEN {$time} AND {$end_time}");
            $thucthu = $dathanhtoan - $tienam*-1;
  
    ?>
            <tr>
                <td><?= $stt ?></td>
                <td><a href="<?= $link ?>" target="_blank"><?= $obj->name ?></a></td>
                <td><?= $obj->phone ?></td>
                <?php if ($adminuser->group_id==1) {
                    $models_group  = new Models_Groupz();
                    $gr = $models_group->getObject($obj->group_id)->name;
                    ?>
                <td><?=$gr?></td>

                    <?php

                } ?>
                <td class="text-info"><?= number_format($obj->balance) ?></td>
                <td class="text-success"><?= $total_cong < 0 ? 0 : number_format($total_cong) ?></td>
                <td class="text-warning"><?= number_format($dathanhtoan) ?></td>
                <td class="text-danger"><?= number_format($thucthu) ?></td>
                <td><?= date('d/m/Y H:i:s', $obj->time) ?></td>
                <td>     <?php if ($adminuser->group_id==1||$adminuser->group_id==2): ?>
                    <button type="button" class="btn <?=$obj->status==2?'btn-success':''?> btn-xs btn-blockz" data="<?= $obj->getId() ?>"><?=$obj->status==1?'Block':'Unblock'?></button>
        <?php endif ?>
                    <button type="button" class="btn btn-warning btn-xs btn-reset" data="<?= $obj->getId() ?>">Reset</button>
                    <?php if ($adminuser->group_id==1): ?>
                    <button type="button" class="btn btn-danger btn-xs btn-delete" data="<?= $obj->getId() ?>">Delete</button>&nbsp;
                        
                    <?php endif ?>


                </td>
            </tr>
    <?php
        }
    ?>
            <tr>
                <td colspan="4">Tổng kết</td>
                <td class="text-info"><?= number_format($total_du) ?></td>
                <td class="text-success"><?= $total < 0 ? 0 : number_format($total) ?></td>
                <td class="text-warning"><?= number_format($total_dathanhtoan) ?></td>
                <td class="text-danger"><?= number_format($total_thucthu) ?></td>
                <td colspan="1"></td>
            </tr>
</table>