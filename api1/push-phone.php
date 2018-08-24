
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
   <div class="col-sm-6 text-left">
      <h4>Thêm số cần nạp tiền</h4>
      <p>Vui lòng nhập số điện thoại, loại thuê bao, số tiền cần nạp:</p>
   </div>


   <div class="col-sm-12">
      <div>
         <table class="table table-hover media-list">
            <thead>
               <tr>
                  <th>STT</th>
                  <th width="200px;">Số điện thoại</th>
                  <th style="width: 120px; text-align: center">Nhà mạng</th>
                  <th style="width: 120px; text-align: center">Loại</th>
                  <th class="text-right" style="width: 150px; text-align: center">Số tiền</th>
                  <th class="text-right" style="text-align: center">Gộp</th>
                  <th class="text-right">Thanh toán</th>
                  <th class="text-right"></th>
               </tr>
            </thead>
            <tbody>
         

               <!-- end ngRepeat: item in invoice.items -->
                  <tr class="element-form">
                  <td>1</td>
                  <td><input type="text"  class="form-control input-small" placeholder="09.., 01.." name ="number_phone" style="min-width: 110px"></td>
                  <td><img src="images/unknow.png" style="width:70%; min-width: 80px"></td>
                  <td>
                     <select name="type" class="form-control" style="min-width: 120px">
                        
                        <option value="1">Trả trước</option>
                        <option value="0">Trả sau</option>
                        <option value="2">FTTH</option>

                     </select>
                  </td>
                  <td class="text-right"><input type="text" class="form-control input-mini" name="price" style="text-align: right; min-width: 100px"></td>
                  <td>
                     <select name="autoJoin" class="form-control" style="min-width: 70px">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                     </select>
                  </td>
                  <td class="text-right order-total" cur_total="0" style="min-width: 100px">0</td>
                  <td class="text-right cusor" style="color: red">[<a class="xElement" style="color: red">X</a>]</td>
               </tr>
               <!-- end ngRepeat: item in invoice.items -->
               <tr id="element-button">
                  <td><button id="add-element" class="btn btn-small"><i class="fa fa-plus"></i> Thêm số</button></td>
                  <td></td>
                  <td></td>
                  <td class="text-right">Tổng:</td>
                  <td class="text-right" id="total" style="font-weight: bold">0</td>
                  <td></td>
                  <td class="text-right" id="total_order" style="font-weight: bold">0</td>
                  <td></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <div class="col-sm-12">
      <div class="line"></div>
      <div class="row">
         <div class="col-sm-5">
         
        
            <div class="form-group"><button type="submit" class="btn btn-success" onclick="window.location='/khop-the.php'"><i class="fa fa-arrow-left"></i> Quay lại</button> </div>
         </div>
         <div class="col-sm-7 text-right">
            <div class="form-group col-sm-8">
               <label class="control-label col-sm-6">Mật khẩu giao dịch:</label>
               <div class="col-sm-6"><input type="password" name="password" class="form-control"></div>
            </div>
            <div class="form-group col-sm-2"><button id="ktButton" type="submit" class="btn login-button"><i class="fa fa-arrow-right"></i> Nạp tiền</button></div>
         </div>
      </div>
   </div>
  

       <div class="col-sm-6 hidden-xs">   
       <form method="post" enctype="multipart/form-data" class="form-inline" action="/process_file.php">
                    <div class="form-group">
                        <label class="control-label">Select File</label>
                        <input type="file" class="file" name="fileToUpload">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Upload</button>
</br>
</br>
              <p>
                   <a href="demoupload_phone.xlsx">Download File Mẫu</a>
                 <br>Chú ý cột "Loai thue bao", các giá trị sẽ là : 1: viettel, 2:mobi, 3:vina
                 <br>Trả sau : 0, Trả trước : 1, FTTH : 2
                 <br>Cột "Nạp Gộp" có 2 giá trị : 0 là không gộp nhiều thẻ, 1 là nạp gộp nhiều thẻ.
              </p>
                </form>

   </div>
   <div class="col-sm-6">
      <strong>Lưu ý:</strong>
      <div>
         <ul>
            <li>Mệnh giá nạp tiền phải là bội số của: <span style="font-weight: bold; color: #ff0000">10.000đ.</span></li>
            <li>Mệnh giá nạp tiền phải <span style="font-weight: bold; color: #ff0000">&gt;= 50.000đ.</span></li>
            <li>Với thuê bao Trả sau yêu cầu đã <span style="font-weight: bold; color: #ff0000">đăng ký thanh toán cước</span> bằng thẻ cào. <a href="/guide"><span style="color: #4cae4c">(Xem hướng dẫn)</span></a></li>
         </ul>
      </div>
   </div> 
</div>

      <!-- end ngIf: discount != null -->
   </div>
</div>
<script>

  var numof_vt =['086', '096', '097', '098', '0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169'];
  var numof_mb =['090', '093', '0120', '0121', '0122', '0126', '0128', '089'];
  var numof_vn =['091', '094', '0123', '0124', '0125', '0127', '0129','088'];

  $('#add-element').click(function() {
$( "#element-button" ).before( '<tr class="element-form"><td>'+($(".element-form").length+1)+'</td><td><input type="text"  class="form-control input-small" placeholder="09.., 01.." name="number_phone" style="min-width: 110px"></td><td><img src="images/unknow.png" style="width:70%; min-width: 80px"></td><td><select name="type" class="form-control" style="min-width: 120px"><option value="1">Trả trước</option><option value="0">Trả sau</option><option value="2">FTTH</option></select></td><td class="text-right"><input type="text" class="form-control input-mini" name="price" style="text-align: right; min-width: 100px"></td><td><select name="autoJoin" class="form-control" style="min-width: 70px"><option value="1">Yes</option><option value="0">No</option></select></td><td class="text-right order-total" cur_total="0" style="min-width: 100px">0</td><td class="text-right cusor" style="color: red">[<a class="xElement" style="color: red">X</a>]</td></tr>');
  });  
  $('.media-list').on('click','.xElement',function() {
  $(this).closest(".element-form").remove();
  });
  function tinhTien(parent) {
    var phoneval = parent.find('input[name=number_phone]').val().trim();
  var type = parent.find('select[name=type]').val().trim();
  var price = parent.find('input[name=price]').val().trim();
   var data = $.ajax({
   url: '/ajax/phone/order-calculation.php',
   type: 'POST',
   async:false,
   dataType: 'json',
   data: {
    phone:phoneval,
    type:type,
    price:price
  },
   success:function(data) {
if (data.code) {
  location.reload();
  return;
}else{
  return data;
}
   }
 });
 return total =data.responseJSON.standard_total;
  }
  function checkPhone(parent) {
        var thisImg = parent.find('img');
        var type =parent.find('select[name=type]').val();
        console.log(type);
  var phoneval = parent.find('input[name=number_phone]').val().trim().toLowerCase();

  if ((phoneval.substring(0, 2)=='01'&&phoneval.length==11||(phoneval.substring(0, 2)=='09'||phoneval.substring(0, 2)=='08')&&phoneval.length==10)||(type==2)) {
  if (phoneval.substring(0, 2)=='01') {
  var subphone = phoneval.substring(0, 4);
}else{
  var subphone = phoneval.substring(0, 3);
}
if (numof_vt.indexOf(subphone)>-1) {
  thisImg.attr('src', 'images/vtt.png');
  parent.find('.order-total').html(tinhTien(parent));
  
tinhTong();
  
  return true;
}else if (numof_mb.indexOf(subphone)>-1) {
  thisImg.attr('src', 'images/vms.png');
  parent.find('.order-total').html(tinhTien(parent));
  
tinhTong();
  return true;

}else if (numof_vn.indexOf(subphone)>-1) {
 thisImg.attr('src', 'images/vnp.png');
 parent.find('.order-total').html(tinhTien(parent));
  
tinhTong();
 return true;

}
else if (type==2) {
 thisImg.attr('src', 'images/vtt.png');
 parent.find('.order-total').html(tinhTien(parent));
tinhTong();
 return true;

}else{
 thisImg.attr('src', 'images/unknow.png');
}

  }else{
 thisImg.attr('src', 'images/unknow.png');
  }
  parent.find('.order-total').html('0');
  parent.find('.order-total').attr('cur_total','0');
tinhTong();
  return false;
  }
   $('.media-list').on('blur','input[name=number_phone]',function() {
    checkPhone($(this).closest(".element-form"));
  });

   $('.media-list').on('blur','input[name=price]',function() {

    checkPhone($(this).closest(".element-form"));
  });
   $('.media-list').on('change','select[name=type]',function() {

    checkPhone($(this).closest(".element-form"));
  });
   $('.media-list').on('click','.xElement',function() {
    var i=0;
      $.each($('.element-form'), function(index, val) {
i++;
$(this).find('td:eq(0)').html(i);
  });
  $(this).closest(".element-form").remove();
     checkPhone($(this).closest(".element-form"));
  });

function tinhTong() {
 var data_test=[];
 $.each($(".element-form"), function(index,val) {
  var phone=$(this).find('input[name=number_phone]').val();
  var type = $(this).find('select[name=type]').val();
var price = $(this).find('input[name=price]').val();
var cur_data = {phone:phone,type:type,price:price};
 data_test.push(cur_data);
});
 var data = $.ajax({
   url: '/ajax/phone/orders-calculation.php',
   type: 'POST',
   async:false,
   dataType: 'json',
   data: {data_test},
   success:function(data) {
if (data.code) {
  location.reload();
  return;
}else{
  return data;
}
   }
 });
 var total =data.responseJSON;
 $("#total").html(total.total);
$("#total_order").html(total.standard_total);
}

$( "#ktButton" ).click(function() {
var data=[];
  $.each($('.element-form'), function(index, val) {
var phone = $(this).find('input[name=number_phone]').val();
var type = $(this).find('select[name=type]').val();
var price = $(this).find('input[name=price]').val();
var join  =$(this).find('select[name=autoJoin]').val();
var cur_data = {phone:phone,type:type,price:price,join:join};

data.push(cur_data);
     /* iterate through array or object */
  });
$.ajax({
   url: '/ajax/push-phone.php',
   type: 'POST',
   dataType: 'json',
   data: {data:data,password:$("input[name=password]").val()},
   success:function(data) {
   alert(data.msg);
   if (data.code==0) {
    window.location = '/khop-the.php';
   }
   }
 });;
  /* alert($("#ok ul li:nth-child(2)").html());
  
  alert($("#ok input:eq(0)").val());*/
 
});
</script>
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
