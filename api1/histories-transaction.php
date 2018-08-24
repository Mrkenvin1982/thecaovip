
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

            <div class="text-left">
         <h4>Lịch sử giao dịch:</h4>
      </div>
      <div class="row">
      <?php if ($adminuser->group_id!=3): ?>
                      <div class="form-horizontal col-sm-4">
               <div class="form-group">
                  <label class="control-label col-sm-2">Tìm:</label>
                  <div class="col-sm-5"><input type="text" class="form-control" placeholder="Tên đại lí" id="username"></div>
                   <div class="col-sm-5"><input type="text" class="form-control" placeholder="Số ĐT đại lí" name="target" id="userphone"></div>
               </div>
            </div>
      <?php endif ?>
                 <!--  <div class="form-horizontal col-sm-2">
                                <div class="form-group">
                 <div class="col-sm-12"><input type="text" class="form-control" placeholder="Số ĐT đại lí" name="target"></div>
                                </div>
                             </div> -->
            <div class="form-horizontal col-sm-2">
               <div class="form-group">
                  <label class="control-label col-sm-3">Loại:</label>
                  <div class="col-sm-9">
                     <select name="status" class="form-control">
                        <option value="100">Tất cả</option>
                        <option value="0">Chuyển tiền</option>
                        <option value="1">Nhận tiền</option>
                        <option value="2">Khớp thẻ</option>
                        <option value="3">Trừ tiền</option>
                        <option value="4">Back gd</option>
                        <?php if ($adminuser->group_id!=3): ?>
                          <option value="5">Hoa hồng</option>
                        <?php endif ?>

      <!-- 0: chuyển tiền, 1: nhận tiền, 2: nạp tiền phone, 3: format   -->
                     </select>
                          
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
      <div class="row">
    <div class="col-md-12 col-xs-12"  id="loading_data">
      
            </div>
</div>

      <!-- end ngIf: discount != null -->
   </div>
</div>
<script>
   
   $(function() {
load(1);
   });
   function load(cur_page) {
                               $.ajax({
                url : '/ajax/load_history.php',
                           dataType: 'html',
                type : 'GET',

                data : {
                  target : $("input[name=target]").val(),
                  status : $("select[name=status]").val(),
                  start_date : $("#startDate").val(),
                  end_date : $("#endDate").val(),
cur_page :cur_page

                },
                beforeSend : function() {
                    $('#loading_data').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                error : function() {
                    $("#loading_data").html('Loading data lỗi, Vui lòng refresh lại trang!');
                },
                success : function(data) {
                    $("#loading_data").html(data);
                }
            });
   }

              
</script>
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
