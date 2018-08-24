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

$models_user = new Models_Users();
    $checkid='';
if (isset($phone)) {
$uss = $models_user->getObjectByCondition('',array('phone'=>$phone));
if (is_object($uss)) {
  $checkid =" and userid = {$uss->getId()}";
}
}

    $cst='';
if ($status!=100) {
        if($status==99){
                     $cst= " and canthanhtoan =0 ";
                }
                elseif ($status==-1) {
                    $cst = " and canthanhtoan <0 ";
                }
                else {
                     $cst = " and (status = $status and canthanhtoan >0 ) ";

            
                }
}
      $typecheck='';
   if (in_array($type, array(0,1,2))) {
      $typecheck= " and type =$type ";
   }elseif($type==10){
$typecheck= " and gop =0 ";
   }
   elseif($type==11){
$typecheck= " and gop =1 ";
   }

$target=strtoupper($target);

if (substr($target, 0,1)=='T') {
$target = substr($target, 1);
$checkst= "id = '{$target}' and";
}else{
$target = substr($target, 1);
   $checkst= "(id LIKE '%{$target}%' or phone LIKE '%{$target}%') and";
}

$models_phones = new Models_Phones();
$models_user = new Models_Users();
$arr_st = explode('/', trim($start_date));
$start_date = mktime(0,0,0,$arr_st[1],$arr_st[0],$arr_st[2]);

$arr_en = explode('/', trim($end_date));
$end_date = mktime(23,59,59,$arr_en[1],$arr_en[0],$arr_en[2]);
$checkuser ='';


$query_total = "SELECT * FROM Phones WHERE $checkst time BETWEEN $start_date and $end_date $checkid  $cst $typecheck order by id desc"; /*and time BETWEEN $start_date and $end_date $checkid $checkuser $cst $typecheck*/
$count = $models_phones->customQuery("SELECT sum(canthanhtoan) sumcan ,sum(dathanhtoan) sumda FROM Phones WHERE $checkst time BETWEEN $start_date and $end_date $checkid $checkuser $cst $typecheck");

$canthanhtoan=0;
$dathanhtoan=0;
if (count($count)>0) {
$canthanhtoan=$count[0]->sumcan;
$dathanhtoan=$count[0]->sumda;
   
}
     $paging->text_sql = $query_total;
    $result_pag_data = $paging->GetResult('Phones');
    $pag =$paging->Load();
?>

            <table class="table table-hover media-list">
               <thead>
                  <tr>
                     <th>ĐH</th>
                        <th width="150px;">Đại lí</th>
                     <th>Thời gian</th>
                     <th>Nhà mạng</th>
                     <th>Số nạp</th>
                     <th>Loại</th>
                     <th>Số tiền</th>
                     <th>Gộp</th>
                     <th>Đã nạp</th>
                     <th>Trạng thái</th>
                     <th>Thao tác</th>
                  </tr>
               </thead>
               <tbody>
                <?php foreach ($result_pag_data as $rs): ?>
                   <tr>

                     <?php if ($rs->canthanhtoan=='') {
                        $rs->canthanhtoan=0;
                        
                     } ?>
                     <td><a href="sua.php?id=<?=$rs->getId()?>">T<?=$rs->getId()?></a></td>
                     <td> <?=$models_user->getObject($rs->userid)->name?></td>
                     <td><?=date('H:i:s d/m/Y',$rs->time)?></td>
                     <td>
                        <div >
                           <?php switch ($rs->loai) {
                              case 1:
                               echo '<img src="/images/vtt.png" style="width:70%; min-width: 80px">';
                                 break;
                              case 2:
                               echo '<img src="/images/vms.png" style="width:70%; min-width: 80px">';
                                 break;
                              case 3:
                               echo '<img src="/images/vnp.png" style="width:70%; min-width: 80px">';
                                 break;
                              default:
                                 // code...
                                 break;
                           } ?>
                        </div>
                     </td>
                     <td style="font-weight: bold"><?=$rs->phone?></td>
                     <td>
                        <?php if ($rs->type==0) {
                        echo 'Trả sau';
                      }elseif ($rs->type==1) {
                        echo 'Trả trước';
                      }elseif ($rs->type==2) {
                        echo 'FTTH';
                      }else{
echo 'Không xác định';
                      } ?>
                     </td>
                     <td style="font-weight: bold" class="editable"><?=number_format($rs->canthanhtoan)?></td>
                     <td style="font-style: italic">
                        <!-- ngIf: item.autoJoin == 'Y' -->
                        <div title="Đơn hàng sẽ được nạp bằng nhiều thẻ mệnh giá khác nhau"><?php if ($rs->gop==0): ?>
                           <span class="text-danger">No</span>
                           <?php else: ?>
                              <span class="text-success">Yes</span>
                        <?php endif ?></div>
                        <!-- end ngIf: item.autoJoin == 'Y' --><!-- ngIf: item.autoJoin == 'N' -->
                     </td>
                     <td class="text-success" style="font-weight: bold"><?=number_format($rs->dathanhtoan)?></td>
                     <td >
                        <!-- ngIf: item.status == 0 --><!-- ngIf: item.status == 99 -->
                        <div>
                       <?php
                if($rs->canthanhtoan <= 0){
                    echo "<span class='text-success' id='status_" . $rs->phone . "'>Hoàn thành!</span>";
                }
                else {
                    if($rs->status == 0){
                        echo "<span class='text-danger' id='status_" . $rs->phone . "'>Không hoạt động</span>";
                    }
                    elseif($rs->status == 1){
                        echo "<span class='text-warning' id='status_" . $rs->phone . "'>Chờ xử lý</span>";
                    }
                    elseif($rs->status == 2){
                        echo "<span class='text-warning' id='status_" . $rs->phone . "'>Đang xử lý...</span>";
                    }
                    elseif($rs->status == 3){
                        echo "<span class='text-danger' id='status_" . $rs->phone . "'>SDT không thể xử lý, kiểm tra lại!</span>";
                    }
                    elseif($rs->status == 4){
                        echo "<span class='text-danger' id='status_" . $rs->phone . "'>Bị khoá!</span>";
                    }
                    elseif($rs->status == 5){
                        echo "<span class='text-danger' id='status_" . $rs->phone . "'>Đã xoá!</span>";
                    }
                }
            ?>  

<!--       <div class="progress" style="min-width: 80px">
                        <div class="progress-bar progress-bar-striped active progress-bar-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:0>%">0>%</div>
                     </div> -->

                        
                        </div>
                     </td>
                     <td>
                        <div style="min-width: 50px">
                        <?php 

        if($rs->canthanhtoan > 0) {
                    if($rs->status == 1) {
                     ?>
<a onclick="pausePhone(<?=$rs->getId()?>,<?=$cur_page?>)" title="Tạm dừng"><i class="fa fa-pause text-info"></i></a> &nbsp;
                 <?php
                    }
                    elseif($rs->status == 0 || $rs->status == 3) {
                     ?>
<a onclick="startPhone(<?=$rs->getId()?>,<?=$cur_page?>)" title="Tiếp tục"><i class="fa fa-play text-success"></i></a> &nbsp;
<a onclick="refund(<?=$rs->getId()?>,<?=$cur_page?>)" title="Hoàn tiền" ><i class="fa fa-undo text-danger"></i></a> &nbsp;

                     <?php
                    }

                }
                         ?>
                        </div>
                        <!-- end ngIf: item.status == 99 --><!-- ngIf: item.status == 98 -->
                     </td>
                  </tr>
                <?php endforeach ?>
                  

               </tbody>
               <tfoot>
                  <tr>
                     <td colspan="6" style="text-align: right">Tổng:</td>
                     <td style="font-weight: bold"><?=number_format($canthanhtoan)?></td>
                     <td></td>
                     <td style="font-weight: bold" class="text-success"><?=number_format($dathanhtoan)?></td>
                     <td colspan="10" style="text-align: right">Có <strong><?=$paging->num_row?></strong> items. <?php if ($paging->num_row>10): ?>
                        (<?=$cur_page."/".$pag["count"]?>)
                     <?php endif ?></td>
                  </tr>
                  <tr class="no-border">
                     <td colspan="11" style="text-align: right">
        
      <!-- ngIf: 1 < pages.length -->
     <!--  <ul class="pagination">
        ngIf: boundaryLinksngIf: directionLinks
        <li  ><a>‹</a></li>
        end ngIf: directionLinksngRepeat: pageNumber in pages track by $index
        <li class="active"><a>1</a></li>
        end ngRepeat: pageNumber in pages track by $index
        <li><a>2</a></li>
       <li><a>3</a></li>
       <li><a>4</a></li>
       <li><a>5</a></li>
       <li><a>6</a></li>
       <li><a>7</a></li>
       <li><a>...</a></li>
       <li><a>26</a></li>
     
     
        end ngRepeat: pageNumber in pages track by $indexngIf: directionLinks
        <li><a>›</a></li>
        end ngIf: directionLinksngIf: boundaryLinks
     </ul> -->
     <?php 
       
  if ($paging->num_row>10) {
    echo $pag['pagination']; 
     
  }?>
      <!-- end ngIf: 1 < pages.length -->
</td>
                  </tr>
               </tfoot>
            </table>