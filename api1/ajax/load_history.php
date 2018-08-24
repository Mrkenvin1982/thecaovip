<?php
include '../config.php';
require_once 'pagination/paging_ajax.php'; 

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

    $cst='';
if (in_array($status, array(0,1,2,3,4,5))) {
       /* 0: chuyển tiền, 1: nhận tiền, 2: nạp tiền phone, 3: format*/
   $cst=" and h.orders = $status";
}
   



$models_histories = new Models_Histories();
$models_us = new Models_Users();
$arr_st = explode('/', trim($start_date));
$start_date = mktime(0,0,0,$arr_st[1],$arr_st[0],$arr_st[2]);

$arr_en = explode('/', trim($end_date));
$end_date = mktime(23,59,59,$arr_en[1],$arr_en[0],$arr_en[2]);
$checkuser ='';
if ($adminuser->group_id!=1) {
  $checkuser =" and (u.refer = {$adminuser->getId()} OR u.id = {$adminuser->getId()})";
}
$clause ='';
if ($target!='') {
  $clause =" AND u.phone = '$target'";
}
$query_total = "SELECT h.user_id user_id,u.refer refer,u.phone phone,u.name user_name, h.cur_balance cur_balance,h.up_balance up_balance, h.money money,h.time time,h.note note,h.orders orders,h.status `status` FROM Histories h JOIN Users u ON h.user_id = u.id WHERE h.time BETWEEN $start_date and $end_date $checkuser $cst $clause order by h.id desc";
/*echo $query_total;
exit;*/
/*     echo $query_total;
    exit;
$query_total = "SELECT * FROM Histories WHERE time BETWEEN $start_date and $end_date $checkuser $cst order by id desc";*/ /*and time BETWEEN $start_date and $end_date $checkuser $cst*/
$listful = $models_histories->customQuery($query_total);
$tongthanhtoan = 0;
foreach ($listful as $value) {
  $tongthanhtoan+=$value->money;
}
/*$count = $models_histories->customQuery("SELECT IFNULL(SUM(h.up_balance-h.cur_balance), 0) besum FROM Histories h JOIN Users u ON h.user_id = u.id HAVING h.time BETWEEN $start_date and $end_date $checkuser $cst $clause order by h.id desc")[]]->besum;*/
     $paging->text_sql = $query_total;
    $result_pag_data = $paging->GetResult('Histories');
    $pag =$paging->Load();
    //SELECT h.user_id user_id,u.phone phone, h.cur_balance cur_balance,h.up_balance up_balance, h.up_balance-h.cur_balance money,h.time time,h.note note,h.orders orders,h.status `status` FROM Histories h JOIN Users u ON h.user_id = u.id HAVING u.phone='0967581301' 
/*        echo $query_total;
    exit;*/
//"SELECT * FROM Histories WHERE admin_id = {$adminuser->getId()} ORDER BY id DESC LIMIT 0, 50"
/*$lists_his = $models_histories->customFilter('',array('user_id'=>$adminuser->getId()),'id DESC',50);*/
?>
            <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <?php if ($adminuser->group_id!=3) {

  echo "<th>Tài khoản</th>";
}
 ?>
                                 
                                <th>Số tiền</th>
                                <th>Số dư trước</th>
                                <th>Số dư sau</th>
                                <th>Thời gian</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>   

<?php
$stt=0;
foreach ($result_pag_data as $obj) {
    $stt++;
?>
    <tr>
        <td><?= $stt ?></td>
       <?php if ($adminuser->group_id!=3) {
  echo "<td>$obj->user_name</td>";
}
      ?>   
  
        <td class="<?= $obj->money > 0 ? 'text-danger' : 'text-warning' ?>"><?= $obj->money > 0 ? ' + ' . number_format($obj->money) : number_format($obj->money) ?> VND</td>
        <td class="text-info"><?= number_format($obj->cur_balance) ?> VND</td>
        <td class="text-success"><?= number_format($obj->up_balance) ?> VND</td>
        <td><?= date('d/m/Y H:i:s', $obj->time) ?></td>
        <td><?= json_decode($obj->note)->msg ?></td>
    </tr>
<?php
    }
?>
</tbody>
<tfoot>
          <tr>
            <?php if (in_array($status, array(0,1,2,3,4,5))): ?>
              <td colspan="<?=$adminuser->group_id!=3?'2':''?>">Tổng</td>
              <td colspan="3" style="text-align: left;"><?=number_format($tongthanhtoan>0?$tongthanhtoan:$tongthanhtoan*-1)?> VNĐ</td>
            <?php endif ?>
                     <td colspan="10" style="text-align: right">Có <strong><?=$paging->num_row?></strong> items. <?php if ($paging->num_row>10): ?>
                        (<?=$cur_page."/".$pag["count"]?>)
                     <?php endif ?></td>
                  </tr>
    <tr class="no-border">
                     <td colspan="11" style="text-align: right">
     <?php 
       
  if ($paging->num_row>10) {
    echo $pag['pagination']; 
     
  }?>
      <!-- end ngIf: 1 < pages.length -->
</td>
                  </tr>
</tfoot>
                    </table>