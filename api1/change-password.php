
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
     <div class="page-header text-center">
            <h1>Thay đổi mật khẩu đăng nhập</h1>
        </div>
      <div class="row">
          <div class="col-md-12">
               <form method="post" novalidate="">
                    <div class="form-group">
                        <div class="alert alert-danger">Wellcome....</div>
                    </div>
                    <div class="form-group">
                      <label for="pass">Mật khẩu cũ</label>
                      <input type="password" class="form-control" name="old_password" placeholder="Old Password">
                    </div>
                    <div class="form-group">
                      <label for="pass">Mật khẩu mới</label>
                      <input type="password" class="form-control" name="new_password" placeholder="New Password">
                    </div>
                    <div id="btn-change">
                        <button type="submit" class="btn btn-default">Thay đổi</button>
                    </div>
                  </form>
          </div>
      </div>

      <!-- end ngIf: discount != null -->
   </div>
</div>
<script>
   $(function() {
            // xu ly form
            $("form").ajaxForm({
                  dataType : 'json',
                  url: '/ajax/changepass.php',
                  beforeSubmit : function() {
                      $("#btn-change").html('Loading...');
                  },
                  success: function(data) {        
                      if(data.code === 0) {
                          location.href = 'index.php';
                      }
                      else {
                          $(".alert").html(data.msg);
                      }
                  },
                  complete : function() {
                      $("#btn-change").html('<button type="submit" class="btn btn-default">Thay đổi</button>');
                  }
            });
        });
              
</script>
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
