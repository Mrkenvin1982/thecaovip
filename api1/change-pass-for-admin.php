
<?php 
$page = 'transfer';
include 'module/head.php';
include 'module/top-nav.php';


 ?>
       <div class="container">
   <div class="line"></div>
   <div class="content">
   <div class="form-group">
      <h4>Đổi mật khẩu đăng nhập và mật khẩu giao dịch</h4>
      <p>Vui lòng nhập số điện thoại hoặc tên đại lý muốn đổi mật khẩu:</p>
   </div>
   <div class="row">
      <div class="form-horizontal col-sm-10">
         <form id="fchangpassforadmin" method="post">
                     <div class="form-group">
               <label class="control-label col-sm-4">Tên tài khoản:</label>
               <div class="col-sm-8"><input type="text" class="form-control" id="username" name="username" placeholder="Tên tài khoản..."></div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Số điện thoại:</label>
               <div class="col-sm-8"><input type="text" class="form-control" id="userphone" name="userphone" placeholder="SDT..." autocomplete="off"></div>
            </div>
         

            
            <div class="form-group">
               <label class="control-label col-sm-4"></label>
            </div>
               <div class="form-group">
               <label class="control-label col-sm-4">Mật khẩu cần đổi:</label>
               
               <div class="col-sm-8"> 
               <label class="checkbox-inline"><input type="checkbox"  name="field_change" value="" id="is_log">Đăng nhập</label>
               <label class="checkbox-inline"><input type="checkbox" name="field_change" value="" id="is_trans">Giao dịch</label>
            </div>

    
            </div> 
            <div class="form-group" id="for-login">
               <label class="control-label col-sm-4"></label>
               <div class="col-sm-8"><input type="text" class="form-control" id="is_log_pass"  placeholder="Mật khẩu đăng nhập...&nbsp;"></div>
            </div>
            <div class="form-group" id="for-trans">
               <label class="control-label col-sm-4"></label>
               <div class="col-sm-8"><input type="text" class="form-control" id="is_trans_pass"  placeholder="Mật khẩu giao dịch...&nbsp;"></div>
            </div>

            <div class="form-group">
               <div class="col-sm-offset-4 col-sm-8" id="#btn-change"><button onclick="change(event)" class="btn login-button"><i class="fa fa-arrow-right"></i> Lưu</button></div>
            </div>
         </form>
   
      </div>
   </div>
</div>

</div>
                    <script>
                   $(function () {
        $('#for-trans').hide();
        $('#for-login').hide();


        //show it when the checkbox is clicked
        $('#is_trans').on('click', function () {
            if ($(this).prop('checked')) {
                $('#for-trans').fadeIn();
            } else {
                $('#for-trans').hide();
            }
        });
             $('#is_log').on('click', function () {
            if ($(this).prop('checked')) {
                $('#for-login').fadeIn();
            } else {
                $('#for-login').hide();
            }
        });
    });
      function change(event) {
         event.preventDefault();
                    let field = {
                     is_log:{
                        stt:0,
                        val:''},
                     is_trans:{
                        stt:0,
                        val:''}
                    }; 
        if ($("#is_log").prop('checked')) {
           field.is_log.val=   $('#is_log_pass').val();
               field.is_log.stt=1;
            }
             if ($("#is_trans").prop('checked')) {
          field.is_trans.val=  $('#is_trans_pass').val();
               field.is_trans.stt=1;
            }

$.ajax({
   url: '/ajax/users/changepassforadmin.php',
   type: 'POST',
   dataType: 'json',
   data: {
      field: field,
      userphone:$("#userphone").val()},
           beforeSend : function() {
                      $("#btn-change").html('Loading...');
                  },
                  success: function(data) {  

                      alert(data.msg);
                    $("#btn-change").html('<button onclick="change(event)" class="btn login-button"><i class="fa fa-arrow-right"></i> Lưu</button>');
                    if (data.code==0) {
                           $('#for-trans').hide();
        $('#for-login').hide();
                      $("#fchangpassforadmin")[0].reset();

                    }
                  }
});

                   }
            </script>
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
