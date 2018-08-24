<style>
    .link-ttdonate{
    float: left;
    margin-top: 20px;
}
.link-ttdonate .st-link-ttdonate{
    padding: 5px 15px;
    border: 1px solid #2f4050;
    border-radius: 10px !important;
    margin-left: 15px;
    text-align:center;
    
}
.link-ttdonate .st-link-ttdonate a{
    color: #888888;
}
.link-ttdonate span:hover{
    background:#2f4050;
}
.link-ttdonate span:hover a{
    color:#fff;
    text-decoration:none;
}
</style>
<div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
    
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Chào: <?=$admin_logged->getUsername()?> </span>
                </li>
                <li>
                    <a href="<?=$admin_url?>/logout.php">
                        <i class="fa fa-sign-out"></i> Đăng xuất
                    </a>
                </li>
            </ul>

        </nav>
        </div>