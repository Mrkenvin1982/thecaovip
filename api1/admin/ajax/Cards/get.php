<?php 
include '../../../config.php'; 
require_once '../pagination/paging_ajax.php'; 
foreach($_GET as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}
if(!is_object($admin_logged)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
$arr_st = explode('/', trim($start_date));
$start_date = mktime(0,0,0,$arr_st[1],$arr_st[0],$arr_st[2]);

$arr_en = explode('/', trim($end_date));
$end_date = mktime(23,59,59,$arr_en[1],$arr_en[0],$arr_en[2]);
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


$models_card = new Models_Cards();

            $query_total = "SELECT * FROM Cards WHERE time BETWEEN $start_date and $end_date ";

     $paging->text_sql = $query_total;
$list = $models_card->customQuery($query_total);
    $result_pag_data = $paging->GetResult('Cards');
    $pag =$paging->Load();
    $total_price=0;


    foreach ($list as $value) {
$total_price+=$value->price;
    }

?>

<table class="table table-hover media-list">
   <thead>
         <tr>
        <td>#</td>
        <td>Số điện thoại</td>
        <td>Pin</td>
        <td>Seri</td>
        <td>Mệnh giá</td>
        <td>Thời gian</td>
         </tr>
   </thead>

   <tbody>
 <?php foreach ($result_pag_data as $card):$i++ ?>
    <tr>
      <td><?=$i?></td>
      <td><?php $models_phone = new Models_Phones();
      echo $models_phone->getObject($card->phone_id)->phone; ?></td>
      <td><?=$card->pin?></td>
      <td><?=$card->seri?></td>
      <td><?=number_format($card->price)?></td>
      <td><?=date('h:i:s d/m/Y',$card->time)?></td>
    </tr>
  <?php endforeach ?>
  
  
  

   </tbody>
   <tfoot>

      <tr>
         <td colspan="4" style="text-align: right">Tổng:</td>
         <td  style="font-weight: bold"><?= $total_price <= 0 ? 0 : number_format($total_price) ?>VNĐ</td>
         

                <td   style="text-align: right">Có <strong><?=$paging->num_row?></strong> items. <?php if ($paging->num_row>10): ?>
                        (<?=$cur_page."/".$pag["count"]?>)
                     <?php endif ?></td>

      </tr>
      <tr class="no-border">
         <td colspan="6" style="text-align: right">
        <?php 
  if ($paging->num_row>10) {
    echo $pag['pagination']; 
  }?>
         </td>
      </tr>
   </tfoot>
</table>
