
<?php 
$page = 'khopthe';
include 'module/head.php';
include 'module/top-nav.php';


 ?>
    <style>
      .prioritize{
        background: #ff6666;
      }
          .prioritize:hover{
        background: #ff6666 !important;
      }

    </style>   <div class="container">
<div class="container">
   <div class="line"></div>
         <div class="row">
        <?php
                if(isset($_SESSION['msgerr'])) {
                    echo '<div class="alert alert-danger">';
                    foreach ($_SESSION['msgerr'] as $err) {
                         echo "- " . $err . "<br/>";
                    }
              
                    echo '</div>';
                }
            ?>
                    <?php
                if(isset($_SESSION['msgsuc'])) {
                    echo '<div class="alert alert-success">';
                    foreach ($_SESSION['msgsuc'] as $succ) {
                         echo "- " . $succ . "<br/>";
                    }
              
                    echo '</div>';
                }
            ?>
            <?php
  
                unset($_SESSION['msgsuc']);
                unset($_SESSION['msgerr']);
            ?>
      </div>
   <!-- uiView:  -->
   <div>
      <div class="text-left">
         <h4>DS Khớp thẻ</h4>
      </div>
      <div class="row">
            <div class="form-horizontal col-sm-3">
               <div class="form-group">
                <div class="row">  <label class="control-label col-sm-3">Tìm:</label>

                  <div class="col-sm-9"><input type="text" class="form-control" placeholder="Số ĐT hoặc mã ĐH" name="target"></div>
</div>
               </div>
            </div>
            <div class="form-horizontal col-sm-2">
               <div class="form-group">
                  <div class="row">
                    <label class="control-label col-sm-1"></label>
                  <div class="col-sm-10">
                   <select name="status" class="form-control">
                        <option value="100">Tất cả</option>
                        <option value="99">Hoàn thành</option>
                        <option value="0">Không hoạt động</option>
                        <option value="1">Chờ xử lí</option>
                        <option value="2">Đang xử lí</option>
                        <option value="3">Lỗi</option>
                        <option value="4">Bị khóa</option>
                        <option value="5">Đã xóa</option>
                        <option value="-1">Âm tiền</option>
                     </select>
                   </div>
             
                          
                  </div>



               </div>
            </div>
                     <div class="form-horizontal col-sm-2">
               <div class="form-group">
                  <div class="row">
                    <label class="control-label col-sm-1"></label>
                  <div class="col-sm-10">
                   <select name="type" class="form-control">
                        <option value="100">Tất cả</option>
                        <option value="1">Trả trước</option>
                        <option value="0">Trả sau</option>
                        <option value="2">FTTH</option>
                        <option value="10">Không gộp</option>
                        <option value="11">Gộp</option>

                     </select>
                   </div>
             
                          
                  </div>



               </div>
            </div>
            <div class="form-horizontal col-sm-4">
               <div class="form-group">
                  <div class="col-sm-5" ><input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy" readonly="readonly" id="startDate" value="<?=date('d/m/Y')?>"></div>
                  <div class="col-sm-2" style="padding: 0px;margin-right: -47px; margin-top:7px"><i class="fa fa-arrow-right"></i></div>
                  <div class="col-sm-5"><input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy" readonly="readonly" id="endDate" value="<?=date('d/m/Y')?>"></div>
               </div>
            </div>
            <div class="form-horizontal col-sm-1">
               <div class="form-group">
                  <div class="col-sm-12"><button  class="btn search-button" onclick="load(1)"><i class="fa fa-search"></i></button></div>
               </div>
            </div>
      </div>
      <?php if ($adminuser->group_id==1): ?>
        <div class="row">
            <div class="form-horizontal col-sm-3">
               <div class="form-group">
<div class="row"><label class="control-label col-sm-3">Tên:</label> <div class="col-sm-9"><input type="text" class="form-control" placeholder="Nhập tên đại lí" id="username"></div></div>
               </div>
            </div>
 <div class="form-horizontal col-sm-3">
               <div class="form-group">
<div class="row"><label class="control-label col-sm-5">SĐT:</label><div class="col-sm-7"><input type="text" class="form-control" placeholder="Nhập SĐT đại lí" id="userphone"></div></div>

               </div>
            </div>
      
         
      </div>
      <?php endif ?>
       <div class="row">

           <div class="col-md-12">

            </div><label class="control-label">Chọn nhiều:</label> <button class="btn btn-default btn-sm" onclick="executeActions('pause')"><i class="fa fa-pause text-info"></i> Tạm dừng đơn đã chọn</button>
<button class="btn btn-default btn-sm" onclick="executeActions('start')"><i class="fa fa-play text-success"></i> Tiếp tục đơn đã chọn</button>
<button class="btn btn-default btn-sm" onclick="executeActions('refund')"><i class="fa fa-undo text-danger"></i> Hoàn tiền đơn đã chọn</button>
<button class="btn btn-default btn-sm" id="onMe"><i class="fa fa-info text-info"></i> <b> <?=$adminuser->group_id==1?'Chỉ xem đơn hàng của tôi':'Xem tất cả đơn hàng'?></b></button></div>  
      
         
      </div>
      <div class="col-sm-12">
         <div id="result-table">

         </div>
         <div class="text-right">
            <button class="btn btn-app btn-sm" onclick="window.location='/push-phone.php';"><i class="fa fa-plus-circle"></i> Thêm đơn hàng</button>
   
            &nbsp; 
            <button class="btn btn-default btn-sm" id="exportBtn"><i class="ace-icon fa fa-download bigger-160"></i> Export</button>
    <!-- <button class="btn btn-default btn-sm"><i class="fa fa-trash text-danger"></i> Xóa đơn đã chọn</button> -->
         </div>
      </div>
  <!--     <script class="ng-scope">$('.search-date').val(getCurrentDate());
   
  </script> -->
       <script type="text/javascript">
  var arr_true_id = {
  "'all'": 0
};
var on_me = <?=$adminuser->group_id==1?0:1?>;
$("#onMe").click(function(event) {
  var text ='';
 if (on_me==0) {
text='Xem tất cả đơn hàng';
  
on_me=1;
 }else{
text='Chỉ xem đơn hàng của tôi';

on_me=0;
 }
  $(this).find('b').html(text);
  load(1);
});
var allcheck = 0;
$("#result-table").on('click', 'input[name=check_khop]', function () {
  var thisval = $(this).val();
  var ischeck = 0;
  if ($(this).prop("checked")) {
    ischeck = 1;
  }
  if (thisval == 'all') {

    $('input[name=check_khop]').not(this).prop('checked', this.checked);
    if (!confirm("Có tổng " + $("#total_record").text() + " bạn có muốn chọn tất cả hay chỉ trong trang này? (OK để chọn tất cả!)")) {
      $.each($('input[name=check_khop]'), function (k, v) {
        if ($(this).val() == 'all') {
          return;
        }
        arr_true_id["'" + $(this).val() + "'"] = ischeck;


      });

    } else {
      arr_true_id = {
        "'all'": ischeck
      };
    }
  } else {
    var count_check_in_page = 0;
    $.each($('input[name=check_khop]'), function (k, v) {
      if ($(this).prop("checked") && $(this).val() != 'all') {
        count_check_in_page++;
      }
    });
    if (count_check_in_page < $("input[name=check_khop]:not(input[value='all'])").length) {
      $('input[name=check_khop][value="all"]').prop('checked', false);
    } else {
      $('input[name=check_khop][value="all"]').prop('checked', true);

    }
    arr_true_id["'" + $(this).val() + "'"] = ischeck;

  }

});


$(function () {

  load(1);
 
});
function executeActions(action) {
  var hd;
  switch(action) {
    case 'pause':
        hd='tạm dừng';
        break;
    case 'start':
        hd='tiếp tục';
        break;
    case 'refund':
        hd='hoàn tiền';
        break;
}
    $.ajax({
    url: '/ajax/phone/getListOrders.php',
    dataType: 'json',
    type: 'POST',
    data: {
      <?=$adminuser->group_id==1?'phone : $("#userphone").val(),':''?>
      action:action,
      target: $("input[name=target]").val(),
      arr_true_id: arr_true_id,
      on_me:on_me,
      status: $("select[name=status]").val(),
      start_date: $("#startDate").val(),
      end_date: $("#endDate").val(),
      type: $("select[name=type]").val()

    },
    success: function (data) {
         if (data.code==0) {
          var mess = "Bạn có đồng ý "+hd+" cho đơn hàng: "+data.msg;
   }else{
    var mess = data.msg;
   }

if (confirm(mess)) {

  $.ajax({
    url: '/ajax/phone/executeActions.php',
    dataType: 'json',
    type: 'POST',
    data: {
      <?=$adminuser->group_id==1?'phone : $("#userphone").val(),':''?>
      action:action,
      target: $("input[name=target]").val(),
      arr_true_id: arr_true_id,
      on_me:on_me,
      status: $("select[name=status]").val(),
      start_date: $("#startDate").val(),
      end_date: $("#endDate").val(),
      type: $("select[name=type]").val()

    },
    success: function (data) {
         if (data.code==0) {
           var cur_page = 1;
  if ($('.pagination li.active a').length>0) {
    cur_page = parseInt($('.pagination li.active a').html());
  }

       load(cur_page);
   }
      alert(data.msg);

    }
  });
}
    }
  });
   
}
function load(cur_page) {


 
  $.ajax({
    url: '/ajax/phone/get.php',
    dataType: 'html',
    type: 'GET',

    data: {
      <?=$adminuser->group_id==1?'phone : $("#userphone").val(),':''?>
      target: $("input[name=target]").val(),
      arr_true_id: arr_true_id,
      on_me:on_me,
      status: $("select[name=status]").val(),
      start_date: $("#startDate").val(),
      end_date: $("#endDate").val(),
      cur_page: cur_page,
      type: $("select[name=type]").val()


    },
    beforeSend: function () {
      $('#result-table').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
    },
    error: function () {
      $("#result-table").html('Loading data lỗi, Vui lòng refresh lại trang!');
    },
    success: function (data) {
      $("#result-table").html(data);
    }
  });

}
$("#exportBtn").click(function () {


  var param = $.param({
    <?=$adminuser->group_id==1?'phone : $("#userphone").val(),':''?>
    target: $("input[name=target]").val(),
    status: $("select[name=status]").val(),
    start_date: $("#startDate").val(),
    end_date: $("#endDate").val(),
    on_me:on_me,
      type: $("select[name=type]").val()
  });
  window.location = '/ajax/phone/export-excel.php?' + param;

});
                        </script>
   </div>
</div>
</div>

         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
