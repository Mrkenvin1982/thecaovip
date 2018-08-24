<?php
include '../../config.php'; 
require_once '../pagination/paging_ajax.php'; 

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
if ($type==1) {
     $check ="AND (msg like '%hanh toan thanh cong cho thue bao%' or msg like '%uy khach da nap thanh cong%')";
}
elseif ($type==2) {
    $check ="AND (msg NOT like '%thanh toan thanh cong cho thue bao%' and msg NOT like '%uy khach da nap thanh cong%' and msg NOT like '%Nạp thẻ thành công%')";
}elseif ($type==3) {
    $check ="AND (msg like '%ERROR%' or msg like '%+CUSD: 4%')";
}
elseif ($type==4) {
    $check ="AND (msg like '%Hien tai he thong dang ban%')";
}
elseif ($type==5) {
    $check ="AND (msg like '%trong ngay %' or msg like '%khong duoc chap nhan, vui long%' or msg like '%toi da 5 giao dich%') ";
}
$search=" AND ((msg like '%$target%') or (pin like '%$target%') or (seri like '%$target%'))";
$query ="SELECT * FROM LogCards WHERE id > 54264 AND time BETWEEN $start_limit AND $end_limit $check $search ORDER BY id DESC";
  $paging = new paging_ajax();
   $paging->class_pagination = "pagination";
   $paging->class_active = "active";
   $paging->class_inactive = "inactive";
   $paging->class_go_button = "go_button";
   $paging->class_text_total = "total";
   $paging->class_txt_goto = "txt_go_button";
   $paging->per_page = 50;  
   $paging->page = $cur_page;
$paging->text_sql = $query;
    $result_pag_data = $paging->GetResult('LogCards');
    $pag =$paging->Load();

?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Pin</th>
            <th>Seri</th>
            <th>Price</th>
            <th>Thời gian</th>
            <th>MSG</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stt = 0;
        $total = 0;
        foreach ($result_pag_data as $obj) {
            $stt++;
        ?>
            <tr>
                <td><?= $stt ?></td>
                <td><?= $obj->pin ?></td>
                <td><?= $obj->seri ?></td>
                <td><?= $obj->price ?></td>
                <td><?= date('d/m/Y - H:i:s', $obj->time) ?></td>
                <td><?= $obj->msg ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
    <tfoot>
                  <tr>
                     
                    
                     <td colspan="10" style="text-align: right">Có <strong id="total_record"><?=$paging->num_row?></strong> items. <?php if ($paging->num_row>10): ?>
                        (<?=$cur_page."/".$pag["count"]?>)
                     <?php endif ?></td>
                  </tr>
                    <tr ></tr>

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