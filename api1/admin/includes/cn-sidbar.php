<nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> 
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold" style="text-transform: uppercase;"><?php $acc = $_SESSION['admin'];echo $acc->username ?></strong>
                             </span><!--  <span class="text-muted text-xs block">Chi tiết <b class="caret"></b></span> </span>--> </a> 
                           <!--  <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="logout.php">Logout</a></li>
                            </ul> -->
                        </div>
                        <div class="logo-element">
                            IN+
                        </div>
                    </li>
                    <li class="<?php if( $page == 'auto'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Auto/sua.php"><i class="fa fa-user" title="Auto" aria-hidden="true"></i> <span class="nav-label">Auto khớp thẻ</span>  </a>
                    </li>
                    <li class="<?php if( $page == 'users'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Users/danhsach.php"><i class="fa fa-user" title="Người dùng" aria-hidden="true"></i> <span class="nav-label">Người dùng</span>  </a>
                    </li>
                    <li class="<?php if( $page == 'lsu'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Histories/danhsach.php"><i class="fa fa-user" title="Lịch sử" aria-hidden="true"></i> <span class="nav-label">Lịch sử</span>  </a>
                    </li>
                    <li class="<?php if( $page == 'cardsS'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/CardStores/danhsach.php"><i class="fa fa-user" title="Cửa hàng thẻ" aria-hidden="true"></i> <span class="nav-label">Kho thẻ</span>  </a>
                    </li>
                               <li class="<?php if( $page == 'cards'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Cards/danhsach.php"><i class="fa fa-user" title="Cards" aria-hidden="true"></i> <span class="nav-label">Cards</span>  </a>
                    </li>
                     <li class="<?php if( $page == 'phones'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Phones/danhsach.php"><i class="fa fa-user" title="Khớp thẻ" aria-hidden="true"></i> <span class="nav-label">Khớp thẻ</span>  </a>
                    </li>
                      <li class="<?php if( $page == 'tb'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Notification/sua.php?id=2"><i class="fa fa-user" title="Thông báo" aria-hidden="true"></i> <span class="nav-label">Thông báo</span>  </a>
                    </li>
                    
                    <li class="<?php if( $page == 'tbnap'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Notification/sua.php?id=1"><i class="fa fa-user" title="Thông báo nạp" aria-hidden="true"></i> <span class="nav-label">Thông báo nạp</span>  </a>
                    </li>
                    <li class="<?php if( $page == 'tbkm'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Notification/sua.php?id=3"><i class="fa fa-user" title="Thông báo khuyến mãi" aria-hidden="true"></i> <span class="nav-label">Thông báo khuyến mãi</span>  </a>
                    </li>
                       <li class="<?php if( $page == 'setting'){echo 'active';} ?>">
                        <a href="<?= $base_url ?>/admin/modules/Setting/"><i class="fa fa-user" title="Thông báo" aria-hidden="true"></i> <span class="nav-label">Cài đặt hệ thống</span>  </a>
                    </li>
                </ul>

            </div>
        </nav>
          <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Tìm kiếm" class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <a class="m-r-sm text-muted welcome-message" href="<?= $admin_url ?>/index.php" ><i class="fa fa-lock" aria-hidden="true"></i> Quản trị</a>
                </li>
                 <li>
                    <a class="m-r-sm text-muted welcome-message" href="<?= $admin_url ?>/user-manager.php" ><i class="fa fa-lock" aria-hidden="true"></i> Quản lí admin</a>
                </li>
                <li>
                    <a target="_blank" class="m-r-sm text-muted welcome-message" href="<?= $base_url ?>/index.php" ><i class="fa fa-link" aria-hidden="true"></i> Trở về trang chủ</a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/admin/logout.php">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>