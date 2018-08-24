<?php $page = 'phones' ?>
<?php require_once '../../../config.php'; ?>
<?php include('../../includes/cn-head.php');  ?>

<?php include('../../includes/cn-sidbar.php');  ?>

<!-- tiêu đề page-->
<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-9">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Khớp thẻ</font></font></h2>
   </div>
   <div class="col-lg-3">
      <?php include('../../includes/toolbar.php');  ?>
   </div>
</div>
<!-- tiêu đề page--> 
<style type="text/css">

  table.table-bordered.dataTable th{
    font-size: 15px;
    font-weight: 600;
  }
</style>  
<!-- nội dung-->
      <div class="">
   <div class="line"></div>
   <!-- uiView:  -->
   <div class="content" ">
      <!-- ngIf: discount != null -->
      <div class="row">
        
      </div>

<div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="page-header row">
                  <div class="col-md-3"> <h4>Danh sách số điện thoại cần thanh toán </h4></div>
                  <div class="col-md-9"> <h4 id="load_keep"></h4></div>
                </div>

           <div class="row">
            <div class="form-horizontal col-sm-3">
               <div class="form-group">
                  <div class="row"> <div class="col-md-12">
                     <label class="control-label col-sm-3">Tìm:</label>

                  <div class="col-sm-9"><input type="text" class="form-control" placeholder="Số ĐT hoặc mã ĐH" name="target"></div>


                  </div> 
</div>
  <br><div class="row"><div class="col-md-12">
    <label class="control-label col-sm-3">Tên:</label> <div class="col-sm-9"><input type="text" class="form-control" placeholder="Nhập tên đại lí" id="username"></div>
  </div></div>
               </div>
            </div>
            <div class="form-horizontal col-sm-2">
               <div class="form-group">
                        <div class="row">
                  <div class="col-sm-12">
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
                     </select></div>
             
                          
                  </div>


  <br><div class="row"><div class="col-sm-12"><input type="text" class="form-control" placeholder="Nhập SĐT đại lí" id="userphone"></div></div>

               </div>
            </div>
            <div class="form-horizontal col-sm-3">
               <div class="form-group">
          <div class="col-sm-12">
                   <select name="type" class="form-control">
                        <option value="100">Tất cả</option>
                        <option value="1">Trả trước</option>
                        <option value="0">Trả sau</option>
                        <option value="2">FTTH</option>
                        <option value="10">Không gộp</option>
                        <option value="11">Gộp</option>

                     </select></div>
               </div>
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
            </div>
        </div>
        <div class="row" style="margin-right: 35px !important">
            <div class="col-xs-12 col-md-12" id="result-table">

</div>
        </div>

      <!-- end ngIf: discount != null -->
   </div>
</div>  
<!-- ./ noi dung--> 
<script>
             $('.datepicker').datetimepicker({
             weekStart: 1,
             todayBtn: 1,
             autoclose: 1,
             todayHighlight: 1,
             startView: 2,
             minView: 2,
             forceParse: 0
         });
  
</script>
<?php include('../../includes/cn-footer.php'); ?>
       <script type="text/javascript">
$( "#result-table" ).on( "click","td.editable", function() {
  alert( $( this ).text() );
});
                          
        function pausePhone(id,page) {
            if(confirm("Bạn chắc chắn muốn tạm dừng thanh toán cho SDT này?")){
                $.ajax({
                    url : '../../ajax/phone/pause.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                            load(page);
                        }
                    }
                });
            }
        }
                function startPhone(id,page) {
            if(confirm("Bạn chắc chắn muốn Bật thanh toán cho SDT này?")){
                $.ajax({
                    url : '../../ajax/phone/start.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                            load(page);
                        }
                    }
                });
            }
        }


                function refund(id,page) {
            if(confirm("Bạn chắc chắn muốn hoàn tiền cho SDT này?")){
                $.ajax({
                    url : '../../ajax/phone/refund.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                             load(page);
                        }
                    }
                });
            }
        }
                          $(function() {
load(1);



      $( "#username" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "../../ajax/transfer/get.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#username').val(ui.item.name); // display the selected text
                $('#userphone').val(ui.item.phone); // save selected id to input
                return false;
            }
        });
                $( "#userphone" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "../../ajax/transfer/get2.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#username').val(ui.item.name); // display the selected text
                $('#userphone').val(ui.item.phone); // save selected id to input
                return false;
            }
        });
                          });
                          function loadKeep(){
                            $.ajax({
  url: '../../ajax/phone/getKeep.php',
  success:function(data){
    $("#load_keep").html(data);
  }
});
                          }
                          function load(cur_page) {
                                                
                                    $.ajax({
                url : '../../ajax/phone/get.php',
                dataType: 'html',
                type : 'GET',

                data : {
                    phone : $("#userphone").val(),
                  target : $("input[name=target]").val(),
                  status : $("select[name=status]").val(),
                  type: $("select[name=type]").val(),
                  start_date : $("#startDate").val(),
                  end_date : $("#endDate").val(),
cur_page :cur_page

                },
                beforeSend : function() {
                    $('#result-table').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                error : function() {
                    $("#result-table").html('Loading data lỗi, Vui lòng refresh lại trang!');
                },
                success : function(data) {
                  loadKeep();
                    $("#result-table").html(data);
                }
            });
                       
                          }
           
                        </script>
