   <body>
      <div>
         <div class="container-fluid" id="top">
            <div class="container">
               <ul class="nav navbar-nav">
                  <li><a href="<?=$base_url?>"><span class="glyphicon glyphicon-home"></span> Trang Chủ</a></li>
               </ul>
               <div id="user-menu">
                  <ul class="nav navbar-nav navbar-right">
                        <?php if (isset($adminuser)): ?>
               <li class="dropdown">
      <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><span id="name"><?=$adminuser->name?></span> (Số dư: <span id="balance" style="font-weight: bold"><?=number_format($adminuser->balance)?> </span>đ) <b class="caret"></b></a>
      <ul class="dropdown-menu">
         <li><a href="/infouser.php"><i class="glyphicon glyphicon-user"></i> Thông tin tài khoản</a></li>
         <li><a href="/change-password.php"><i class="glyphicon glyphicon-lock"></i> Đổi mật khẩu đăng nhập</a></li>
         <li><a href="/change-trans-password.php"><i class="glyphicon glyphicon-lock"></i> Đổi mật khẩu giao dịch</a></li>

         <li class="divider"></li>
         <li><a href="/logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
      </ul>
   </li>
                 <?php else: ?>
                     <li><a href="/login.php" ><span class="fa fa-sign-in"></span> Đăng nhập</a></li>

              <?php endif ?>
                       
                  </ul>

               </div>
            </div>
         </div>
               <?php $models_notice = new Models_Notification();
            $note = $models_notice->getObjectByCondition('',array('status'=>1,'orders'=>2)); ?>
         <!--  notice -->
         <?php if (is_object($note)): ?>
            <div class="container-fluid">
            <div class="container">

<h3><b style="color: red"><marquee><?=$note->content?></marquee></b></h2>
            </div>
         </div>
         <?php endif ?>
         <!--  notice -->

         <div class="navbar" role="navigation">
            <div class="container menu">
               <div class="navbar-header hidden-xs"><a class="navbar-brand" href="<?=$base_url?>"><img src="images/logo.png"></a></div>
               <div class="navbar-collapse collapse" id="navmenu">
                  <ul class="nav navbar-nav navbar-right">
                     <li><a href="/khop-the.php">Khớp thẻ</a></li>
                     <li><a href="/transfer.php">Chuyển số dư</a></li>
                     <li><a href="/histories-transaction.php">Lịch sử giao dịch</a></li>
                     <li><a href="/user.php">Tài khoản</a></li>
                <!--      <li><a href="/guide">Hướng dẫn</a></li>
                <li><a href="">Tin tức</a></li> -->
                  </ul>
               </div>
            </div>
         </div>
         <script>

         </script>