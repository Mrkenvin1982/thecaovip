
<?php 
$page = 'transfer';
include 'module/head.php';
include 'module/top-nav.php';


 ?>
       <div class="container">
   <div class="line"></div>
   <div class="content">
   <div class="form-group">
      <h4>Chuyển số dư</h4>
      <p>Vui lòng nhập số điện thoại nhận và số tiền muốn chuyển:</p>
   </div>
   <div class="row">
      <div class="form-horizontal col-sm-10">
         <form id="faddbalance" method="post">
                     <div class="form-group">
               <label class="control-label col-sm-4">Tên tài khoản nhận:</label>
               <div class="col-sm-8"><input type="text" class="form-control" id="username" name="username" placeholder="Tên tài khoản..."></div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Số điện thoại nhận:</label>
               <div class="col-sm-8"><input type="text" class="form-control" id="userphone" name="userphone" placeholder="SDT..." autocomplete="off"></div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Số tiền:</label>
               <div class="col-sm-8"><input type="number" class="form-control" name="money" id="money" placeholder="Số tiền...&nbsp;"></div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4"></label>
               <div class="col-sm-8 " id="toword"> [<strong>0</strong>đ]</div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Mật khẩu giao dịch:</label>
               <div class="col-sm-8"><input type="text" class="form-control" name="trans_pass" id="trans_pass" placeholder="Mật khẩu giao dịch...&nbsp;"></div>
            </div>
            <div class="form-group">
               <div class="col-sm-offset-4 col-sm-8" id="btn-congtien"><button type="submit" class="btn login-button"><i class="fa fa-arrow-right"></i> Chuyển tiền</button></div>
            </div>
         </form>
      </div>
   </div>
</div>

</div>
<script>



           
            
</script>
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
