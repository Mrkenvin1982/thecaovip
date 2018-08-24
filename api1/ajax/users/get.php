<?php 
include '../../config.php'; 
require_once '../pagination/paging_ajax.php'; 

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
   $paging = new paging_ajax();
   $paging->class_pagination = "pagination";
   $paging->class_active = "active";
   $paging->class_inactive = "inactive";
   $paging->class_go_button = "go_button";
   $paging->class_text_total = "total";
   $paging->class_txt_goto = "txt_go_button";

   // KHỞI TẠO SỐ PHẦN TỬ TRÊN TRANG
    $paging->per_page = 10;   

    // LẤY GIÁ TRỊ PAGE THÔNG QUA PHƯƠNG THỨC POST
    $paging->page = $cur_page;
$search ='';
if ($target!='') {
  $search =" and phone like '{$target}%' ";
}

$models_users = new Models_Users();
$models_histories = new Models_Histories();
$models_phones = new Models_Phones();
$checkgr ='';
if (in_array($group, array(2,3))) {
   $checkgr =" and group_id = $group"; 
}
        if ($adminuser->group_id==1) {
            $query_total = "SELECT * FROM Users WHERE status != 0 AND group_id > {$adminuser->group_id} $search $checkgr ORDER BY orders ASC, id DESC";
            if (isset($uid)) {
              $uid = intval(Library_String::decryptstr(base64_decode($uid), $key_enc));
               $query_total = "SELECT * FROM Users WHERE status != 0 AND refer = $uid $search $checkgr ORDER BY orders ASC, id DESC";
            }
        }else{
$query_total = "SELECT * FROM Users WHERE status != 0 AND refer = {$adminuser->getId()} $search $checkgr ORDER BY orders ASC, id DESC";

        }

        $listusers = $models_users->customQuery($query_total);
     $paging->text_sql = $query_total;
    $result_pag_data = $paging->GetResult('Users');
    $pag =$paging->Load();
?>

<table class="table table-hover media-list">
   <thead>
         <tr>
        <th>#</th>
        <th>Name</th>
        <th>Phone</th>
        <?php if ($adminuser->group_id==1&&!isset($uid)): ?>
        <th>Cấp</th>
        <?php endif ?>
        <th>Số dư</th>
        <th>Tổng tiền</th>
        <th>Đã thanh toán</th>
        <th>Chờ thanh toán</th>
        <th>Tạo lúc </th>
        <th><?php if (isset($uid)): ?>
          &nbsp;<button class="btn btn-default btn-sm " onclick="blockAll('<?=$uid?>')"><i class="fa fa-lock text-danger"></i> Khóa hết</button>
        <?php endif ?></th>
    </tr>
   </thead>

   <tbody>
       <?php


        $stt = 0;
              foreach ($result_pag_data as $obj) {
            $stt++;
                   $link = 'user.php?acc=' . base64_encode(Library_String::encryptStr($obj->getId(), $key_enc));
            $total_cong = $models_histories->getSumByColumn2('money', "user_id = {$obj->getId()}");
      

            $dathanhtoan = $models_phones->getSumByColumn2('dathanhtoan', "userid = {$obj->getId()}");
     $chuathanhtoan =$models_phones->getSumByColumn2('canthanhtoan', "userid = {$obj->getId()} and canthanhtoan>0");

    ?>
            <tr>
                <td><?= $stt ?></td>
                <td><a href="<?= $link ?>" target="_blank"><?= $obj->name ?></a></td>
                <td><?= $obj->phone ?></td>
                <?php if ($adminuser->group_id==1&&!isset($uid)) {
                    $models_group  = new Models_Groupz();
                    $gr = $models_group->getObject($obj->group_id)->name;
                    ?>
                <td><?=$gr?></td>

                    <?php

                } ?>
                <td class="text-info"><?= number_format($obj->balance) ?></td>
                <td class="text-success"><?= $total_cong < 0 ? 0 : number_format($total_cong) ?></td>
                <td class="text-warning"><?= number_format($dathanhtoan) ?></td>
                <td class="text-danger"><?= number_format($chuathanhtoan) ?></td>
                <td><?= date('d/m/Y H:i:s', $obj->time) ?></td>
                <td>     <?php if ($adminuser->group_id!=3): ?>
                <?php if ($adminuser->group_id==1): ?>
                    <i class="fa <?=$obj->status==1?'fa-lock text-danger':'fa-unlock text-success'?> btn-blockz" data="<?= $obj->getId() ?>" text="<?=$obj->status==1?'khóa ':'mở khóa '?><?= $obj->name?>"></i>&nbsp;
                        
                <?php endif ?>
                  <a href="<?= $link ?>" target="_blank"><i class="fa fa-info-circle"></i></a>
        <?php endif ?>


                </td>
            </tr>
    <?php
        }
    ?>
   </tbody>
   <tfoot>

      <tr>
         
                <td colspan="12" style="text-align: right">Có <strong><?=$paging->num_row?></strong> items. <?php if ($paging->num_row>10): ?>
                        (<?=$cur_page."/".$pag["count"]?>)
                     <?php endif ?></td>

      </tr>
      <tr class="no-border">
         <td colspan="11" style="text-align: right">
        <?php 
  if ($paging->num_row>10) {
    echo $pag['pagination']; 
     
  }?>
         </td>
      </tr>
   </tfoot>
</table>