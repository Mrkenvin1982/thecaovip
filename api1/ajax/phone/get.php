`<?php 
include '../../config.php'; 
require_once '../pagination/paging_ajax.php'; 
ini_set('memory_limit', '150M');
$arr_true = $_GET['arr_true_id'];
$all=$arr_true["'all'"];
foreach($_GET as $key => $value) {
    $$key = $value;
if (!is_array($value)) {
      if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
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
$keeps = $models_phones->customQuery("SELECT (SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 10000 AND 19999 and status =1)  '10k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 20000 AND 29999 and status =1)  '20k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 30000 AND 49999 and status =1) '30k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 50000 AND 99999 and status =1)  '50k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 100000 AND 199999 and status =1)  '100k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 200000 AND 299999 and status =1)  '200k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 300000 AND 499999 and status =1)  '300k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 500000 AND 999999 and status =1)  '500k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan >=1000000 and status =1)  '1000k'")[0];
$rs_keep='';
foreach ($keeps as $key=> $val) {
  if (!in_array($key, array('id','phone','loai','type','userid','canthanhtoan','dathanhtoan','gop','last_balance','time','orders','status'))) {
   $rs_keep.= "<span style='font-weight:bold'>".$key."<span>:&nbsp;<span style='font-weight:bold;color:red'>".$val."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
  }
}
$arr_st = explode('/', trim($start_date));
$start_date = mktime(0,0,0,$arr_st[1],$arr_st[0],$arr_st[2]);

$arr_en = explode('/', trim($end_date));
$end_date = mktime(23,59,59,$arr_en[1],$arr_en[0],$arr_en[2]);
$checkuser ='';
if ($on_me==1) {
   $checkuser =" and userid = {$adminuser->getId()}";
}

$query_total = "SELECT * FROM Phones WHERE $checkst time BETWEEN $start_date and $end_date $checkid $checkuser $cst $typecheck order by id desc"; /*and time BETWEEN $start_date and $end_date $checkid $checkuser $cst*/
$count = $models_phones->customQuery("SELECT *,sum(canthanhtoan) sumcan ,sum(dathanhtoan) sumda FROM Phones WHERE $checkst time BETWEEN $start_date and $end_date $checkid $checkuser $cst $typecheck");
/*echo "SELECT sum(canthanhtoan) sumcan ,sum(dathanhtoan) sumda FROM Phones WHERE $checkst time BETWEEN $start_date and $end_date $checkid $checkuser $cst";*/
$canthanhtoan=0;
$dathanhtoan=0;
if (count($count)>0) {
$canthanhtoan=$count[0]->sumcan;
$dathanhtoan=$count[0]->sumda;
}
$listfull = $models_phones->customQuery($query_total);
$checkclick=array();
foreach ($listfull as $value) {
     if (isset($arr_true["'{$value->getId()}'"])) {
      $checkclick[$value->getId()]=$arr_true["'{$value->getId()}'"];
      continue;
  }
     if ($all==1) {
   $checkclick[$value->getId()]=1;
   continue;
}
   $checkclick[$value->getId()]=0;

}

     $paging->text_sql = $query_total;
    $result_pag_data = $paging->GetResult('Phones');
    $pag =$paging->Load();
    $countcheck=0;
    foreach ($result_pag_data as $value) {
  if ($checkclick[$value->getId()]==1) {
    $countcheck++;
  }
}
?>


            <table class="table table-hover media-list">
               <thead>
                  <tr>
                    <th><input type="checkbox" name="check_khop" value="all" <?=$countcheck==count($result_pag_data)?'checked':''?>></th>
                     <th>ĐH</th>
                     <?php if ($adminuser->group_id==1): ?>
                        <th width="150px;">Đại lí</th>
                     <?php endif ?>
                     <th width="150px;">Thời gian</th>
                     <th style="width: 120px; text-align: center">Nhà mạng</th>
                     <th>Số nạp</th>
                     <th>Loại</th>
                     <th>Số tiền</th>
                     <th>Gộp</th>
                     <th>Đã nạp</th>
                     <th class="text-right" style="width: 85px; text-align: center">Trạng thái</th>
                     <th class="text-right" width="85px; text-align: center;">Thao tác</th>
                  </tr>
               </thead>
               <tbody>
                <?php foreach ($result_pag_data as $rs): ?>
                   <tr <?=$rs->orders!=0?'class="prioritize"':''?>>
<td><input type="checkbox" name="check_khop" value="<?=$rs->getId()?>" <?=$checkclick[$rs->getId()]==1?'checked':''?>></td>
                     <?php if ($rs->canthanhtoan=='') {
                        $rs->canthanhtoan=0;
                        
                     } ?>
                     <td>T<?=$rs->getId()?></td>
                    <?php if ($adminuser->group_id==1): ?>
                       <td>
                      <?=$models_user->getObject($rs->userid)->name?>
                     </td>
                    <?php endif ?>
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
                     <td style="font-weight: bold"><?php 
$phone_number = $rs->phone;
//11 số cắt 7 số
//10 số cắt 6 số
if ($adminuser->group_id!=1&&$adminuser->getId()!=$rs->userid) {
  if (strlen($phone_number)==11) {
  $cut = substr($phone_number, 0,7);
  $replace='*******';
}elseif(strlen($phone_number)==10){
   $cut = substr($phone_number, 0,6);
  $replace='******';

}
$phone_number = str_replace($cut,  $replace, $phone_number);
}
echo $phone_number;

                      ?></td>
                     <td>
                      <div><?php if ($rs->type==0) {
                        echo 'Trả sau';
                      }elseif ($rs->type==1) {
                        echo 'Trả trước';
                      }elseif ($rs->type==2) {
                        echo 'FTTH';
                      }else{
echo 'Không xác định';
                      } ?></div>
                        
                     </td>
                     <td style="font-weight: bold"><?=number_format($rs->canthanhtoan)?></td>
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
                     <td class="text-right">
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
                    elseif($rs->status == 2 ||$rs->status == 6){
                        echo "<span class='text-warning' id='status_" . $rs->phone . "'>Đang xử lý...</span>";
                    }
                    elseif($rs->status == 3){
                        if ($rs->type==2) {
                          echo "<span class='text-danger' id='status_" . $rs->phone . "'>Tài khoản FTTH không thể xử lý, kiểm tra lại!</span>";
                        }else{
                         echo "<span class='text-danger' id='status_" . $rs->phone . "'>SDT không thể xử lý, kiểm tra lại!</span>";
                        }
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
                     <td class="text-right">
                        <div style="min-width: 50px">

                        <?php 
        if($rs->canthanhtoan > 0) {
 
            
              
     if ($rs->userid==$adminuser->getId()||$adminuser->group_id==1) {
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
        if ($adminuser->group_id==1) {
                      
               if ($rs->orders==0) {
                        ?>
                 <a onclick="prioritize(<?=$rs->getId()?>,<?=$cur_page?>)" title="Ưu tiên"><i class="fa fa-arrow-circle-up text-success"></i></a> &nbsp;
                      <?php
               }else{
                 ?>
                 <a onclick="prioritize(<?=$rs->getId()?>,<?=$cur_page?>)" title="Bỏ ưu tiên"><i class="fa fa-arrow-circle-down text-danger"></i></a> &nbsp;
                      <?php
               }
                    }
                }
                         ?>
                         
                        </div>
                     </td>
  
                  </tr>
                <?php endforeach ?>
                  

               </tbody>
               <tfoot>
                  <tr>
                     <td colspan="7" style="text-align: right">Tổng:</td>
                     <td style="font-weight: bold"><?=number_format($canthanhtoan)?></td>
                     <td></td>
                     <td style="font-weight: bold" class="text-success"><?=number_format($dathanhtoan)?></td>
                     <td colspan="10" style="text-align: right">Có <strong id="total_record"><?=$paging->num_row?></strong> items. <?php if ($paging->num_row>10): ?>
                        (<?=$cur_page."/".$pag["count"]?>)
                     <?php endif ?></td>
                  </tr>
                    <tr ></tr>
<?php if ($adminuser->group_id==1): ?>
  <tr class="no-border"><td colspan="11" style="text-align: right"><?=$rs_keep?></td></tr>
<?php endif ?>
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