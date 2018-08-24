<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>

    <script type="text/javascript" src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="./node_modules/jquery-form/dist/jquery.form.min.js"></script>
    <script type="text/javascript" src="./node_modules/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    
    <script>
        $(function() {
            // xu ly form
            $("form").ajaxForm({
                  dataType : 'json',
                  url: '/ajax/changetranspass.php',
                  beforeSubmit : function() {
                      $("#btn-change").html('Loading...');
                  },
                  success: function(data) { 
                          $(".alert").html(data.msg);
                         
                      if(data.code === 0) {
                          location.href = 'index.php';
                      }
                    
                  },
                  complete : function() {
                      $("#btn-change").html('<button type="submit" class="btn btn-default">Thay đổi</button>');
                  }
            });
        });
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header text-center">
            <h1>Thay đổi mật khẩu giao dịch</h1>
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-4 col-md-offset-4">
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
    </div>  
</body>
</html>