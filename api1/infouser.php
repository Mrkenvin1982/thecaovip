
<?php 
$page = 'adduser';
include 'module/head.php';
include 'module/top-nav.php';


 ?>
       <div class="container">
   <div class="line"></div>
   <!-- uiView:  -->
   <div class="content">
      <!-- ngIf: discount != null -->
      <div class="row">
         <?php
          $models_discount = new Models_DiscountPercentage();
          $chietkhau = $models_discount->getObjectByCondition('',array('user_id'=>$adminuser->getId()));
        $models_default = new Models_DefaultDiscount();
        $models_prepaid = new Models_PrepaidDiscount();
        $prepaid_vt_discount = $models_prepaid->getObjectByCondition('',array('orders'=>1))->discount;
        $prepaid_mb_discount = $models_prepaid->getObjectByCondition('',array('orders'=>2))->discount;
        $prepaid_vn_discount = $models_prepaid->getObjectByCondition('',array('orders'=>3))->discount;
  $default_discount = $models_default->getLastObject()->discount; ?>
               <div class="col-md-6 col-xs-12" style="float: left;">
                     <div class="alert alert-info">Số điện thoại : <?=$adminuser->phone?>                    <br>
                    Loại tài khoản: <?php $models_gr = new Models_Groupz();
                  echo  $models_gr->getObject($adminuser->group_id)->name ?>
                  <br><span>Số dư: <?=number_format($adminuser->balance)?> VNĐ</span>
                  
                </div>
            </div>
            <div class="col-md-6 col-xs-12" style="float: right;">
                <div class="alert alert-info">UID : <?=$adminuser->getId()?>
                    <br>
                    Scret key : <?=$adminuser->scret_key?>
                    <br>
                    <a href="<?=$base_url?>/demo.add_phone.zip">Tải code demo</a>
                </div>
            </div>
                    <div class="col-md-12 col-xs-12" style="float: right;">
                     <div class="alert alert-warning row">
                  <div class="col-md-12"><h4>Chiết khấu:
               </h4></div>

                 <div class="col-md-6"><span><b style="color: red">Trả sau:</b> Viettel: <?=is_object($chietkhau)?$chietkhau->viettel_percent:$default_discount?>%</span> &nbsp;&nbsp; 
                 <span> Mobi: <?=is_object($chietkhau)?$chietkhau->mobi_percent:$default_discount?>%</span> &nbsp;&nbsp; 
                 <span> Vina: <?=is_object($chietkhau)?$chietkhau->vina_percent:$default_discount?>%</span></div>
                 <div class="col-md-6"><span><span><b style="color: red">Trả trước:</b> Viettel: <?=is_object($chietkhau)?$chietkhau->viettel_percent-$prepaid_vt_discount:$default_discount-$prepaid_vt_discount?>%</span> &nbsp;&nbsp; 
                 <span> Mobi: <?=is_object($chietkhau)?$chietkhau->mobi_percent-$prepaid_mb_discount:$default_discount-$prepaid_mb_discount?>%</span> &nbsp;&nbsp; 
                 <span> Vina: <?=is_object($chietkhau)?$chietkhau->vina_percent-$prepaid_vn_discount:$default_discount-$prepaid_vn_discount?>%</span></div>

                 
                  
                </div>
            </div>
          
        </div>
 

      <!-- end ngIf: discount != null -->
   </div>
</div>

         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
