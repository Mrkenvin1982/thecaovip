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
$search ='';
$target=trim($target);
if ($target!='') {
      $seri=Library_String::encryptStr($target,SERI_PASS);
        $pin=Library_String::encryptStr($target,PIN_PASS);
  $search =" and (pin = '{$pin}' or seri = '{$seri}') ";
}

$models_card = new Models_CardStores();

    $checkst = '';
    if (in_array($status, array(1,0,2))) {
      $checkst = "AND status = $status";
    }
    $orders='orders ASC, id DESC';
  if (in_array($order, array(0,1,2,3))) {
    switch ($order) {
      case 0:
         $orders='time_in DESC, price DESC';
        break;
      case 1:
         $orders='time_out DESC, price DESC';
        break;
      case 2:
         $orders='price DESC, card_type ASC, time_in DESC';
        break;
      case 3:
         $orders='card_type ASC, price DESC, time_in DESC';
        break;                      
    }
  }
       
            $query_total = "SELECT * FROM CardStores WHERE time_in BETWEEN $start_date and $end_date $search $checkst ORDER BY $orders";
/*echo $query_total;

exit();*/
     $paging->text_sql = $query_total;
$list = $models_card->customQuery($query_total);
    $result_pag_data = $paging->GetResult('CardStores');
    $pag =$paging->Load();
    $the_dasudung=0;
    $the_chuasudung=0;
    $the_loi=0;

    foreach ($list as $value) {
      if ($value->status==1) {
            $the_chuasudung++;
            continue;
      }elseif ($value->status==2) {
        $the_loi++;
      }else{
          $the_dasudung++;

      }
    }
?>

<table class="table table-hover media-list">
   <thead>
         <tr>
        <td>#</td>
        <td>ID</td>
        <td>Loại thẻ</td>
        <td>Pin</td>
        <td>Seri</td>
        <td>Mệnh giá</td>
        <td>Ngày hết hạn</td>
        <td>Nhập vào</td>
        <td>Xuất ra</td>

        <td>Trạng thái</td>
        <td>Des</td>   

         </tr>
   </thead>

   <tbody>
 <?php foreach ($result_pag_data as $card):$i++ ?>
    <tr>
      <td><?=$i?></td>
      <td><a href="sua.php?id=<?=$card->getId()?>"><?=$card->getId()?></a></td>
      <td><?php switch ($card->card_type) {
                              case 1:
                               echo '<img src="'.$base_url.'/images/vtt.png" style="width:70%; min-width: 80px">';
                                 break;
                              case 2:
                               echo '<img src="'.$base_url.'/images/vms.png" style="width:70%; min-width: 80px">';
                                 break;
                              case 3:
                               echo '<img src="'.$base_url.'/images/vnp.png" style="width:70%; min-width: 80px">';
                                 break;
                           } ?></td>
      <td><?=Library_String::decryptstr($card->pin,PIN_PASS)?></td>
      <td><?=Library_String::decryptstr($card->seri,SERI_PASS)?></td>
      <td><?=$card->price?></td>
      <td><?=$card->expire_date?></td>
      <td><?=date('h:i:s d/m/Y',$card->time_in)?></td>
            <td><?=$card->time_out==0?'chưa sử dụng hoặc lỗi':date('h:i:s d/m/Y',$card->time_out)?></td>
      <td><?php switch ($card->status) {
                              case 0:
                               echo 'Đã sử dụng';
                                 break;
                              case 1:
                               echo 'Chưa sử dụng';
                                 break;
                              case 2:
                               echo 'Thẻ sai';
                                 break;
                              case 3:
                               echo 'Lỗi';
                              default:
                                 // code...
                                 break;
                           } ?></td>
                           <td><?=$card->result?></td>
    </tr>
  <?php endforeach ?>
  
  
  

   </tbody>
   <tfoot>

      <tr>
         <td colspan="6" style="text-align: right">Tổng:</td>
         <td  style="font-weight: bold"><?= $the_chuasudung < 0 ? 0 : number_format($the_chuasudung) ?> Thẻ chưa sử dụng</td>
         <td  style="font-weight: bold"><?= $the_dasudung < 0 ? 0 : number_format($the_dasudung) ?> Thẻ đã sử dụng</td>
         <td  style="font-weight: bold"><?= $the_loi < 0 ? 0 : number_format($the_loi) ?> Thẻ Lỗi</td>


                <td colspan="2"  style="text-align: right">Có <strong><?=$paging->num_row?></strong> items. <?php if ($paging->num_row>10): ?>
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
