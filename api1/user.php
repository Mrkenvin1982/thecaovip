
<?php
$page = 'user';
include 'module/head.php';
include 'module/top-nav.php';
/*base64_encode(Library_String::encryptStr($obj->getId(), $key_enc)); //encode*/
$check         = 0;
$models_user   = new Models_Users();
$models_groupz = new Models_Groupz();
if (isset($_GET['acc'])) {
    $uid    = $_GET['acc'];
    $userid = intval(Library_String::decryptstr(base64_decode($uid), $key_enc)); //decode
    $user   = $models_user->getObject($userid);
    if (is_object($user)) {
        if ($adminuser->group_id == 1 || $adminuser->getId() == $user->refer) {
            $check = 2;
        }

    }
} else {
    $user                = $models_user->getObject($adminuser->getId());
    $check               = 1;
    $models_prepaid      = new Models_PrepaidDiscount();
    $prepaid_vt_discount = $models_prepaid->getObjectByCondition('', array('orders' => 1))->discount;
    $prepaid_mb_discount = $models_prepaid->getObjectByCondition('', array('orders' => 2))->discount;
    $prepaid_vn_discount = $models_prepaid->getObjectByCondition('', array('orders' => 3))->discount;
    $hotSale             = $models_prepaid->getObjectByCondition('', array('orders' => 4))->discount;

}
$noteSale = $models_notice->getObjectByCondition('', array('orders' => 3));

?>
 <?php if ($check != 0): ?>
   <style>
   .contentUserinfo{
    margin: 10px;
   }
   .table td {
border-top: 1px solid #ffffff !important;
   }
   td.active{
    padding-top: 1px;
   }
   .content-30{
    width: 30%
   }
      .content-70{
    width: 70%
   }


   @media only screen and (max-width: 500px) {
   .stpadding{
    overflow-x: scroll;
   }
      .stpadding{
    padding: 15px 0px;
   }
      .content-70 input{
    width: 120px;
    max-width: 100%;
   }
}
td.active {
  font-weight: bold;
}

 </style>
       <div class="content  container" id="">
         <h2>Quản lí user: <?=$user->name?></h2>
  <div class="panel panel-default col-md-12">

  <div class="content col-md-12">
         <h4>Chi tiết</h4>
  <div class="panel panel-default row">
<div class="col-md-6 panel-body">
<table class="table">
  <tr>
    <td class="text-right active">Tên</td>
    <td class="text-left"><?=$user->name?></td>
  </tr>
  <tr>
    <td class="text-right active">Số điện thoại</td>
    <td class="text-left"><?=$user->phone?></td>
  </tr>
<tr>
  <td class="text-right active">Loại tài khoản</td>
  <td class="text-left">
<?=$models_groupz->getObject($user->group_id)->name?>

    </td>
</tr>
<tr>
  <td class="text-right active">Ngày tạo</td>
  <td class="text-left"><?=date('d/m/Y', $user->time)?></td>
</tr>
<tr>
  <td class="text-right active">Trạng thái</td>

  <td class="text-left">
<?=$user->status == 0 ? 'Khóa' : 'Hoạt động'?>

</td>

</tr>
</table>

</div>
<div class="col-md-6 panel-body">
  <table class="table">
        <tr>
  <td class="text-right active">Số dư</td>
  <td class="text-left">
<?=number_format($user->balance)?> vnđ
</td>
</tr>
<?php if ($adminuser->group_id == 1 && $user->refer != 0): ?>
      <tr>
        <?php $fuser = $models_user->getObject($user->refer);
$link                = 'user.php?acc=' . base64_encode(Library_String::encryptStr($fuser->getId(), $key_enc));?>
  <td class="text-right active">Tài khoản tạo:</td>
  <td class="text-left">
<a href="<?=$link?>" target="_blank"><?=$fuser->name?></a>
</td>
</tr>
<?php endif?>
  </table>


</div>
  </div>

</div>

             <h4>API info</h4>
  <div class="panel panel-default col-md-12">
<div class="panel-body stpadding" >
  <table class="table">

      <tr>
  <td class="text-right active content-30">UID</td>
  <td class="text-left content-70">
<?=$user->getId()?>
</td>
</tr>

 <tr>
  <td class="text-right active content-30" >Scret key</td>

  <td class="text-left content-70">
<?=$user->scret_key?>
</td>

</tr>
<?php if ($check == 1): ?>
   <tr>
  <td class="text-right active content-30" >Code demo</td>

  <td class="text-left content-70">
<a href="<?=$base_url?>/demo.add_phone.zip">Tải code demo</a>

</td>

</tr>
<?php endif?>

  </table>


</div>

  </div>
         <h4>Chiết khấu</h4>
    <?php
$models_discount  = new Models_DiscountPercentage();
$chietkhau        = $models_discount->getObjectByCondition('', array('user_id' => $user->getId()));
$models_default   = new Models_DefaultDiscount();
$default_discount = $models_default->getLastObject()->discount;?>
 <?php if ($check == 2): ?>
   <form class="panel panel-default col-md-12 form-change-discount" method="post">
<div class="panel-body stpadding">
  <table class="table">


      <tr>

  <td class="text-right active content-30">Thẻ viettel</td>

  <td class="text-left content-70">

  <input type="number" name="viettel_percent" value="<?=is_object($chietkhau) ? $chietkhau->viettel_percent : $default_discount?>">%

</td>

</tr>
     <tr>

  <td class="text-right active content-30">FTTH Viettel</td>

  <td class="text-left content-70">

  <input type="number" name="ftth_percent" value="<?=is_object($chietkhau) ? $chietkhau->ftth_percent : $default_discount?>">%

</td>

</tr>
      <tr>

  <td class="text-right active content-30">Thẻ mobi</td>

  <td class="text-left content-70">

  <input type="number" name="mobi_percent" value="<?=is_object($chietkhau) ? $chietkhau->mobi_percent : $default_discount?>">%

</td>

</tr>
      <tr>

  <td class="text-right active content-30">Thẻ vina</td>

  <td class="text-left content-70">

  <input type="number" name="vina_percent" value="<?=is_object($chietkhau) ? $chietkhau->vina_percent : $default_discount?>">%

</td>

</tr>

    <tr>
  <td class="text-right content-30" ><input type="hidden" value="<?=$uid?>" name="acc"></td>

  <td class="text-left content-70" id="btn_save_discount">
<button>Thay đổi</button>
</td>

</tr>

  </table>


</form>
   <?php else: ?>

     <div class="panel panel-default" >
      <div class="panel-body stpadding">
        <?php if ($noteSale->status == 1): ?>
          <div class="col-md-12" style="text-align: center;"><h1><span><b style="color: red"><?=str_replace('{phantram}', $hotSale, $noteSale->content)?></b></span></h1></div>
        <?php endif?>
        <div class="col-md-6  "><span><b style="color: red">Trả sau:</b> Viettel: <?=is_object($chietkhau) ? $chietkhau->viettel_percent+$hotSale : $default_discount?>%</span> &nbsp;&nbsp;
                 <span> Mobi: <?=is_object($chietkhau) ? $chietkhau->mobi_percent+$hotSale : $default_discount?>%</span> &nbsp;&nbsp;
                 <span> Vina: <?=is_object($chietkhau) ? $chietkhau->vina_percent+$hotSale : $default_discount?>%</span></div>
                 <div class="col-md-6 "><span><span><b style="color: red">Trả trước:</b> Viettel: <?=is_object($chietkhau) ? $chietkhau->viettel_percent - $prepaid_vt_discount+$hotSale : $default_discount - $prepaid_vt_discount?>%</span> &nbsp;&nbsp;
                 <span> Mobi: <?=is_object($chietkhau) ? $chietkhau->mobi_percent - $prepaid_mb_discount+$hotSale : $default_discount - $prepaid_mb_discount?>%</span> &nbsp;&nbsp;
                 <span> Vina: <?=is_object($chietkhau) ? $chietkhau->vina_percent - $prepaid_vn_discount+$hotSale : $default_discount - $prepaid_vn_discount?>%</span></div>
<br>
<div class="col-md-12"><span><b style="color: red">FTTH:</b> Viettel: <?=is_object($chietkhau)?$chietkhau->ftth_percent+$hotSale:$default_discount?>%</span></div>
      </div>

     </div>
 <?php endif?>
</div>
  <!--danh sách từ đây-->

  <?php if ($user->group_id != 3): ?>
    <h4>Danh sách đại lí cấp 2</h4>
<div class="panel panel-default col-md-12">
<div class="panel-body stpadding" id="load_accounts">

</div>

  </div>
  <script>

   $(function() {
                  load(1);

   });

            // xu ly form

                 function load(page) {
            $.ajax({
                url : '/ajax/users/get.php',
                data : {
                  uid : '<?=$uid?>',
                    cur_page : page
                },
                beforeSend : function() {
                    $('#load_accounts').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                error : function() {
                    $("#load_accounts").html('Loading data lỗi, Vui lòng refresh lại trang!');
                },
                success : function(data) {
                    $("#load_accounts").html(data);

              $(".btn-blockz").click(function(){
                        if(confirm('Bạn chắc chắn muốn '+$(this).attr('text')+'?')) {
                            $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/blockuser.php',
                                context : $(this),
                                data : {
                                    userId : $(this).attr('data')
                                },
                                beforeSend : function() {
                                    $(this).parent().html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                                },
                                success: function(data) {
                                    if(data.code!= 0) {
                                        load(1);
                                    }
                                    alert(data.msg);

                                }
                            });
                        }
                    });
                }
            });
        }
        function blockAll(uid) {
                  if(confirm('Bạn chắc chắn muốn khóa toàn bộ đại lí cấp 2 của user này?')) {
                            $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/users/blockAll.php',
                                data : {
                                    uid : uid
                                },
                                beforeSend : function() {

                                },
                                success: function(data) {
                                    if(data.code== 0) {
                                        load(1);
                                    }
                                    alert(data.msg);

                                }
                            });
                        }
}
</script>
  <?php endif?>
  <!--danh sách đến đây-->
  </div>

</div>
       </div>
       <script>
                  $(".form-change-discount").ajaxForm({
                  dataType : 'json',
                  url: '/ajax/discount/save.php',
                  beforeSubmit : function() {
              /*        $("#btn_save_discount").html('Loading...');*/
                  },
                  success: function(data) {
                      alert(data.msg);
                      $("#btn_save_discount").html('<button>Thay đổi</button>');
                  }
            });

       </script>
       <?php else: ?>
        <center>
        <h1>Trang lày không tồn tại nhé! pạn back về trang chủ <a href="<?=$base_url?>">"tại đây"!</a></h1>
        </center>
 <?php endif?>
         <?php
include 'module/footer.php';

?>
         <!-- ngIf: alert.alert == 1 -->
