<?php $page = 'setting' ?>
<?php require_once '../../../config.php'; ?>
<?php include('../../includes/cn-head.php');  ?>
<?php include('../../includes/cn-sidbar.php');  ?>
<?php 
       $models_minimum = new Models_MinimumMoney();
        $minimum_ts_object = $models_minimum->getObjectByCondition('',array('orders'=>0)); 
       $minimum_ts =$minimum_ts_object->price;
        $minimum_ftth_object = $models_minimum->getObjectByCondition('',array('orders'=>2)); 
       $minimum_ftth =$minimum_ftth_object->price;
       $minimum_tt_object = $models_minimum->getObjectByCondition('',array('orders'=>1)); 
       $minimum_tt =$minimum_tt_object->price;
       $minimum_gop_object = $models_minimum->getObjectByCondition('',array('orders'=>4)); 
       $minimum_gop =$minimum_gop_object->price;
       $models_one_mil = new Models_OneMilTransactionAccess();
       $one_mil_obj = $models_one_mil->getObject(1);
       $haft_part_obj = $models_one_mil->getObject(6);

       $trans_c2_access_obj = $models_one_mil->getObject(2);
       $models_prepaid = new Models_PrepaidDiscount();
       $viettel_discount = $models_prepaid->getObjectByCondition('',array('orders'=>1));
       $mobi_discount = $models_prepaid->getObjectByCondition('',array('orders'=>2));
       $vina_discount = $models_prepaid->getObjectByCondition('',array('orders'=>3));
       $hotSale = $models_prepaid->getObjectByCondition('',array('orders'=>4));

$prepaid_access_obj = $models_one_mil->getObject(3);
$ftth_access_obj = $models_one_mil->getObject(4);
$postpaid_access_obj=$models_one_mil->getObject(5);
?>
<style type="text/css">
HTML  CSS   Result
Edit on 
.switch-field {
  font-family: "Lucida Grande", Tahoma, Verdana, sans-serif;
  padding: 40px;
    overflow: hidden;
    
}
.switch-field{
  float: left;
    width: 100%;
}
.switch-title {
  margin-bottom: 6px;
}



.switch-field input {
    position: absolute !important;
    clip: rect(0, 0, 0, 0);
    height: 1px;
    width: 1px;
    border: 0;
    overflow: hidden;
}


.label-for {
  float: left;
  display: inline-block;
  width: 60px;
  background-color: #e4e4e4;
  color: rgba(0, 0, 0, 0.6);
  font-size: 14px;
  font-weight: normal;
  text-align: center;
  text-shadow: none;
  padding: 6px 14px;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
  -webkit-transition: all 0.1s ease-in-out;
  -moz-transition:    all 0.1s ease-in-out;
  -ms-transition:     all 0.1s ease-in-out;
  -o-transition:      all 0.1s ease-in-out;
  transition:         all 0.1s ease-in-out;
}

.label-for:hover {
    cursor: pointer;
}

.switch-field input:checked + label {
  background-color: #1ab394;
  -webkit-box-shadow: none;
  box-shadow: none;
  color: #fff
}

.label-for:first-of-type {
  border-radius: 4px 0 0 4px;
}

.label-for:last-of-type {
  border-radius: 0 4px 4px 0;
}
.switch-title{
  font-weight: bold;
  font-size: 15px;
  padding: 5px;
}
.switch-title i{
  font-weight: 300;
}
.setTime{
  width: 100%;
  float: left;
  margin-bottom: 15px
}
.btn-sussces{
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 10px 32px;
    text-decoration: none;
    margin: 4px 2px;
    cursor: pointer;
}
</style>
    <div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-10">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Cài đặt hệ thống</font></font></h2>
   </div>

</div>
  <!-- start --> 
<div class="ibox-content row">
<form method="POST" id="fMinium">
     <div class="col-md-4 row">
     <label for="price col-md-12">Số tiền tối thiểu có thể khớp cho đơn trả trước:</label>
<div class="form-group col-md-12">
 <label class="radio-inline"><input type="radio" name="price" value="10000" <?=$minimum_tt==10000?'checked':''?>>10,000 VNĐ </label>
 <label class="radio-inline"><input type="radio" name="price" value="50000" <?=$minimum_tt==50000?'checked':''?>>50,000 VNĐ </label>
<label class="radio-inline"><input type="radio" name="price" value="100000" <?=$minimum_tt==100000?'checked':''?>>100,000 VNĐ </label>
<label class="radio-inline"><input type="radio" name="price" value="500000" <?=$minimum_tt==500000?'checked':''?>>500,000 VNĐ </label>

</div>
</div>
     <div class="col-md-4 row">
     <label for="price col-md-12">Số tiền tối thiểu có thể khớp cho đơn trả sau:</label>
<div class="form-group col-md-12">
 <label class="radio-inline"><input type="radio" name="price2" value="10000" <?=$minimum_ts==10000?'checked':''?>>10,000 VNĐ </label>
 <label class="radio-inline"><input type="radio" name="price2" value="50000" <?=$minimum_ts==50000?'checked':''?>>50,000 VNĐ </label>
<label class="radio-inline"><input type="radio" name="price2" value="100000" <?=$minimum_ts==100000?'checked':''?>>100,000 VNĐ </label>
<label class="radio-inline"><input type="radio" name="price2" value="500000" <?=$minimum_ts==500000?'checked':''?>>500,000 VNĐ </label>

</div>
</div>
     <div class="col-md-4 row">
     <label for="price col-md-12">Số tiền tối thiểu có thể khớp cho đơn ftth:</label>
<div class="form-group col-md-12">
 <label class="radio-inline"><input type="radio" name="price3" value="10000" <?=$minimum_ftth==10000?'checked':''?>>10,000 VNĐ </label>
 <label class="radio-inline"><input type="radio" name="price3" value="50000" <?=$minimum_ftth==50000?'checked':''?>>50,000 VNĐ </label>
<label class="radio-inline"><input type="radio" name="price3" value="100000" <?=$minimum_ftth==100000?'checked':''?>>100,000 VNĐ </label>
<label class="radio-inline"><input type="radio" name="price3" value="500000" <?=$minimum_ftth==500000?'checked':''?>>500,000 VNĐ </label>

</div>
</div>
<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>
</form>
   </div>
<!-- end -->
  <!-- start --> 
<div class="ibox-content row">
  <div class="col-md-8">
    <label for="price col-md-12">Chỉnh sửa chiết khấu trả trước:</label>

   <form method="post" id="discount_prepaid">
<div class="form-group col-md-12">
  <label>Viettel:</label>
      <input type="number" name="viettel_discount" value="<?=$viettel_discount->discount?>"/>%&nbsp;&nbsp;&nbsp;&nbsp;
        <label>Mobi:</label>
      <input type="number" name="mobi_discount" value="<?=$mobi_discount->discount?>"/>%&nbsp;&nbsp;&nbsp;&nbsp;
        <label>Vina:</label>
      <input type="number" name="vina_discount" value="<?=$vina_discount->discount?>"/>%&nbsp;&nbsp;&nbsp;&nbsp;
    </div>

<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>
   </form>
  </div>

  <div class="col-md-4">
      <label for="price col-md-12">Chiết khấu khuyến mãi:</label>
   <form method="post" id="fHotSale">
<div class="form-group col-md-12">
  
      <input type="number" name="hotSale" value="<?=$hotSale->discount?>"/>%
    </div>

<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>

    
   </form>
  </div>
   </div>
<!-- end -->

 <!-- start --> 
<div class="ibox-content row">
   <div class="col-md-4">
     <form method="post" id="fStatus500" >
     <label for="price col-md-12">GD không gộp cho thẻ 500k:</label>
     <div class="switch-field form-group col-md-12">
      <input type="radio" id="switch_left500k" name="status500k" value="1" <?= ($haft_part_obj->status == 1) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_left500k"> Bật </label>
      <input type="radio" id="switch_right500k" name="status500k" value="0" <?= ($haft_part_obj->status == 0) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_right500k"> Tắt </label>

    </div>

<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>

    
   </form>
   </div>

   <div class="col-md-4">
     <form method="post" id="fStatusOneMil" >
     <label for="price col-md-12">GD không gộp cho thẻ 1tr:</label>
     <div class="switch-field form-group col-md-12">
      <input type="radio" id="switch_left" name="status" value="1" <?= ($one_mil_obj->status == 1) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_left"> Bật </label>
      <input type="radio" id="switch_right" name="status" value="0" <?= ($one_mil_obj->status == 0) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_right"> Tắt </label>

    </div>

<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>

    
   </form>
   </div>

   <div class="col-md-4">
     <form method="post" id="fStatusTrans">
     <label for="price col-md-12">Cho phép chuyển tiền đại lí cấp 2:</label>
     <div class="switch-field form-group col-md-12">
      <input type="radio" id="switch_left2" name="status2" value="1" <?= ($trans_c2_access_obj->status == 1) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_left2"> Bật </label>
      <input type="radio" id="switch_right2" name="status2" value="0" <?= ($trans_c2_access_obj->status == 0) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_right2"> Tắt </label>

    </div>


<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>

    
   </form>
   </div>
   </div>
<!-- end -->
  <!-- start --> 
<div class="ibox-content row">
  <div class="col-md-4">
     <form method="post" id="fStatusPrepaid">

     <label for="price col-md-12">Cho phép up đơn trả trước:</label>
     <div class="switch-field form-group col-md-12">
      <input type="radio" id="switch_left3" name="status3" value="1" <?= ($prepaid_access_obj->status == 1) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_left3"> Bật </label>
      <input type="radio" id="switch_right3" name="status3" value="0" <?= ($prepaid_access_obj->status == 0) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_right3"> Tắt </label>

    </div>


<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>

    
   </form>
  </div>

   <div class="col-md-4">
     <form method="post" id="fStatusPostpaid">
     <label for="price col-md-12">Cho phép up đơn Viettel trả sau:</label>
     <div class="switch-field form-group col-md-12">
      <input type="radio" id="switch_left5" name="status5" value="1" <?= ($postpaid_access_obj->status == 1) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_left5"> Bật </label>
      <input type="radio" id="switch_right5" name="status5" value="0" <?= ($postpaid_access_obj->status == 0) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_right5"> Tắt </label>

    </div>

<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>

    
   </form>
   </div>
   <div class="col-md-4">
     <form method="post" id="fStatusFtth">
     <label for="price col-md-12">Cho phép up đơn FTTH:</label>
     <div class="switch-field form-group col-md-12">
      <input type="radio" id="switch_left4" name="status4" value="1" <?= ($ftth_access_obj->status == 1) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_left4"> Bật </label>
      <input type="radio" id="switch_right4" name="status4" value="0" <?= ($ftth_access_obj->status == 0) ? 'checked' : '' ?>/>
      <label class="label-for" for="switch_right4"> Tắt </label>

    </div>

<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>

    
   </form>
     
   </div>
   </div>
<!-- end -->


<!-- start --> 
<div class="ibox-content row">
  <label for="price col-md-12">Số tiền tối thiểu cho nạp không gộp:</label>

   <form method="post" id="minimum_gop">
<div class="form-group col-md-12">
  
      <input type="number" name="minimum_gop" value="<?=$minimum_gop?>"/>%
    </div>

<div class="form-group col-md-12"><button type="submit" class="btn btn-success">Lưu</button></div>

    
   </form>
   </div>
<!-- end -->


</div>
<script>
  $("#fStatusPrepaid").ajaxForm({
    url: '../../ajax/setting/on_off_setting.php',
    dataType:'json',
    beforeSubmit : function() {
                    
                },
    success: function(data) {        
                alert(data.msg);
                }
  });
    $("#fStatusPostpaid").ajaxForm({
    url: '../../ajax/setting/on_off_setting.php',
    dataType:'json',
    beforeSubmit : function() {
                    
                },
    success: function(data) {        
                alert(data.msg);
                }
  });
    $("#fStatusFtth").ajaxForm({
    url: '../../ajax/setting/on_off_setting.php',
    dataType:'json',
    beforeSubmit : function() {
                    
                },
    success: function(data) {        
                alert(data.msg);
                }
  });
        $("#fHotSale").ajaxForm({
    url: '../../ajax/setting/hotSale.php',
    dataType:'json',
    beforeSubmit : function() {
                    
                },
    success: function(data) {        
                alert(data.msg);
                }
  })
    $("#fMinium").ajaxForm({
    url: '../../ajax/setting/change_minimum.php',
    dataType:'json',
    beforeSubmit : function() {
                    
                },
    success: function(data) {        
                alert(data.msg);
                }
  });
    $("#minimum_gop").ajaxForm({
    url: '../../ajax/setting/change_minimum.php',
    dataType:'json',
    beforeSubmit : function() {
                    
                },
    success: function(data) {        
                alert(data.msg);
                }
  });
    
  $("#fStatusOneMil").ajaxForm({
    url: '../../ajax/setting/on_off_setting.php',
    dataType:'json',
    beforeSubmit : function(data) {
                },
    success: function(data) {        
                alert(data.msg);

                }
  });
  $("#fStatus500").ajaxForm({
    url: '../../ajax/setting/on_off_setting.php',
    dataType:'json',
    beforeSubmit : function(data) {
                },
    success: function(data) {        
                alert(data.msg);

                }
  });
  
    $("#fStatusTrans").ajaxForm({
    url: '../../ajax/setting/on_off_setting.php',
    dataType:'json',
    beforeSubmit : function(data) {
                },
    success: function(data) {        
                alert(data.msg);
                }
  })
        $("#discount_prepaid").ajaxForm({
    url: '../../ajax/setting/change_discount_prepaid.php',
    dataType:'json',
    beforeSubmit : function(data) {
                },
    success: function(data) {        
                alert(data.msg);
                }
  })
    
</script>
<?php include('../../includes/cn-footer.php'); ?>