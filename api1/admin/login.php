<?php 
    include '../config.php';
 ?>
 <!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DONATE | Dashboard</title>
    <link href="<?= $base_url ?>/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/admin/font-awesome/css/font-awesome.css" rel="stylesheet">
    <!-- Toastr style -->
    <link href="<?= $base_url ?>/admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <!-- Gritter -->
    <link href="<?= $base_url ?>/admin/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="<?= $base_url ?>/admin/css/animate.css" rel="stylesheet">
    <link href="<?= $base_url ?>/admin/css/style.css" rel="stylesheet">
    <link href="<?= $base_url ?>/admin/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    
</head>
<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>

           
            <p>Login in. To see it in action.</p>
            <form class="m-t" role="form" id="flogin">
                <div class="form-group">
                    <input type="text" name="account" class="form-control" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required="">
                </div>
                <p id="error"></p>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

               
            </form>
     <?php    include 'includes/cn-footer.php';  ?>
     <script type="text/javascript">
         $("#flogin").ajaxForm({
    dataType: 'json',
    url: 'ajax/login.php',
    type: 'post',
    success: function (data) {
    if (data.code === 0) {
    window.location.href = data.link;
  }else {
      $("#error").html(data.msg);
        }
    }
});
     </script>