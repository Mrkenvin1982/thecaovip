
<?php 
$page = 'adduser';

include 'module/head.php';
include 'module/top-nav.php';

if ($adminuser->group_id==3) {
    header('location:index.php');
    exit();
}
 ?>
       <div class="container">
   <div class="line"></div>
   <!-- uiView:  -->
   <div class="content">
      <!-- ngIf: discount != null -->
      <div class="row">
    <div class="col-md-12 col-xs-12">
                <div class="page-header">
                    <h4>Thêm account</h4>
                </div>
                <form method="post" action="#" id="fadduser" class="">
                    <div class="form-group">
                      <label for="name" class="sr-only">Tên</label>
                      <input type="text" class="form-control" name="name" placeholder="Tên...">
                    </div>
                    <div class="form-group">
                      <label for="phone" class="sr-only">Phone</label>
                      <input type="text" class="form-control" name="userphone" placeholder="SDT..." autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Mật khẩu...">
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password 2</label>
                        <input type="password" class="form-control" name="trans_pass" placeholder="Mật khẩu giao dịch...">
                    </div>
  
                     <div class="form-group">
      <select class="form-control" name="group_id" id="group_id">
        <option value="0">Chọn quyền đại lí</option>
                                  <?php  $models_gr = new Models_Groupz();
     $grs = $models_gr->customFilter('',array('id'=>array($adminuser->group_id, '>')),'id asc');
  ?>
     <?php foreach ($grs as $gr): ?>
        <option value="<?=$gr->getId()?>"><?=$gr->name?></option>
     <?php endforeach ?>
           </select>
                    </div>
<div class="row">
                    <div class="form-group col-md-3">    
                        <input type="number" name="viettel_percent" class="form-control" placeholder="Chiết khấu thẻ viettel..."></div>
                        <div class="form-group col-md-3">  
                        <input type="number" name="ftth_percent" class="form-control" placeholder="Chiết khấu ftth..."></div>
                    <div class="form-group col-md-3">    

                        <input type="number" name="mobi_percent" class="form-control" placeholder="Chiết khấu thẻ mobi..."></div>
                         <div class="form-group col-md-3">  
                        <input type="number" name="vina_percent" class="form-control" placeholder="Chiết khấu thẻ vina..."></div>

                        
                        
                    </div>

                  
                    <div class="form-group" id="btn-addacc">
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>     
                </form>
            </div>
</div>
      <div class="page-header">
                    <h4>Danh sách account</h4>
                     <div class="form-horizontal col-sm-7">
               <div class="form-group row">
                  <label class="control-label col-sm-1">Tìm:</label>
                  <div class="col-sm-3"><input type="text" class="form-control" placeholder="Tên tài khoản" id="username"></div>
                  <div class="col-sm-3"><input type="text" class="form-control" placeholder="Số ĐT" name="target" id="userphone"></div>
                  <div class="col-sm-3">
                    <select name="group_user" class="form-control ui-autocomplete-input">
                    <option value="1" >Tất cả</option>
                    <option value="2" >Cấp 1</option>
                    <option value="3" >Cấp 2</option>
                  </select>
                </div>
<div class="col-sm-2" style="left: 15px"><button  class="btn search-button" onclick="load(1)"><i class="fa fa-search"></i></button></div>
               </div>

            </div>
      
                    <div class="clearfix"></div>
                </div>
        <div class="row">
     <div id="load_accounts" >
</div>
        </div>




      <!-- end ngIf: discount != null -->
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
                   target : $("input[name=target]").val(),
                   group : $("select[name=group_user]").val(),

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
</script>
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
