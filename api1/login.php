
<?php 
$page = 'login';
include 'module/head.php';
include 'module/top-nav.php';


 ?>
         <div class="container">
            <div class="line"></div>
            <!-- uiView:  -->
            <div>
               <h3 >Đăng nhập</h3>
               <p class="alert">Vui lòng nhập số điện thoại và mật khẩu nếu bạn đã đăng ký một tài khoản.</p>
               <div class="row">
                  <div class="form-horizontal col-sm-6">
                     <form method="post">
                        <div class="form-group">
                           <label class="control-label col-sm-4">Số điện thoại:</label>
                           <div class="col-sm-8"><input class="form-control" name="phone"></div>
                        </div>
                        <div class="form-group">
                           <label class="control-label col-sm-4">Mật khẩu:</label>
                           <div class="col-sm-8"><input type="password" class="form-control" name="password"></div>
                        </div>
                        <div class="form-group">
                           <label class="control-label col-sm-4"></label>
                           <div class="col-sm-4"><input type="checkbox" accesskey="r" checked="checked" name="remember"> <span>Ghi nhớ</span></div>
                           <div class="col-sm-4"><a href="/forgot-password"><i class="fa fa-drupal"></i> Quên mật khẩu</a></div>
                        </div>
                        <div class="form-group">
                           <div class="col-sm-offset-4 col-sm-8" id="btn-login"><button type="submit" class="btn login-button"><span class="fa fa-sign-in"></span> Đăng nhập</button></div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <script>
        $(function() {
            // xu ly form
            $("form").ajaxForm({
                  dataType : 'json',
                  url: '/ajax/login.php',
                  beforeSubmit : function() {
                      $("#btn-login").html('Loading....');
                  },
                  success: function(data) {        
                      if(data.code === 0) {
                          location.href = 'index.php';
                      }
                 
                      else {
                          $(".alert").html(data.msg);
                      }
                  },
                  error : function(res) {
                      console.log(res);
                  },
                  complete : function() {
                      $("#btn-login").html('<button type="submit" class="btn login-button"><span class="fa fa-sign-in"></span> Đăng nhập</button>');
                  }
            });
        });
    </script>
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
