
<?php 
$page = 'index';
include 'module/head.php';
include 'module/top-nav.php';


 ?>
       <div class="container">
   <div class="line"></div>
   <!-- uiView:  -->
   <div class="content">
      <!-- ngIf: discount != null -->
      <div class="column title">
   
       
         <div class="col-md-3 col-sm-3 col-xs-12">
            <a href="khop-the.php">
               <div class="cell-icon">
                  <div><img class="image-icon" src="images/khopthe.png"></div>
                  <span>Khớp thẻ</span>
               </div>
            </a>
         </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
            <a href="transfer.php">
               <div class="cell-icon">
                  <div><img class="image-icon" src="images/transfer.png"></div>
                  <span>Chuyển số dư</span>
               </div>
            </a>
         </div>
         <div class="col-md-3 col-sm-3 col-xs-12">
            <a href="histories-transaction.php">
               <div class="cell-icon">
                  <div><img class="image-icon" src="images/trans.png"></div>
                  <span>Lịch sử giao dịch</span>
               </div>
            </a>
         </div>
<?php if ($adminuser->group_id==1): ?>
          <div class="col-md-3 col-sm-3 col-xs-12">
            <a href="card-store.php">
               <div class="cell-icon">
                  <div><img class="image-icon" src="images/khothe.png"></div>
                  <span>Kho thẻ</span>
               </div>
            </a>
         </div>
                   <div class="col-md-3 col-sm-3 col-xs-12">
            <a href="change-pass-for-admin.php">
               <div class="cell-icon">
                  <div><img class="image-icon" src="images/keykey.png"></div>
                  <span>Đổi mật khẩu</span>
               </div>
            </a>
         </div>
   
<?php endif ?>
<?php if ($adminuser->group_id!=3): ?>
 <div class="col-md-3 col-sm-3 col-xs-12">
            <a href="adduser.php">
               <div class="cell-icon">
                  <div><img class="image-icon" src="images/add.png"></div>
                  <span>Thêm tài khoản</span>
               </div>
            </a>
         </div>
<?php endif ?>

         <div class="col-md-3 col-sm-3 col-xs-12">
            <a href="user.php">
               <div class="cell-icon">
                  <div><img class="image-icon" src="images/infouser.png"></div>
                  <span>Thông tin tài khoản</span>
               </div>
            </a>
         </div>
      </div>
      <!-- end ngIf: discount != null -->
   </div>
</div>
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
