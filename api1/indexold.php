<?php
include 'config.php';
$msg_err = $_SESSION['msgerr'];
$msg_suc = $_SESSION['msgsuc'];

if(!is_object($adminuser)) {
    // chuyen huong login
    header('location:login.php');
    exit();
}
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
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>Admin System <small><?= $adminuser->name ?> ( <?= date('m/Y') ?> )</small></h1>

            <div class="pull-right"><a href="logout.php" class="btn btn-danger">Thoát</a></div>
            <div class="pull-right"><a href="changepass.php" class="btn btn-primary">Đổi mật khẩu</a>&nbsp;</div>
            <div class="pull-right"><a href="changetranspass.php" class="btn btn-primary">Đổi mật khẩu giao dịch</a>&nbsp;</div>

            <div class="clearfix"></div>
        </div>
        <div class="row">
            <?php
                if($msg_err) {
                    echo '<div class="alert alert-danger">';
                        foreach ($msg_err as $err) {
                            echo "- " . $err . "<br/>";
                        }
                    echo '</div>';
                }
            ?>
            <?php
                if($msg_suc) {
                    echo '<div class="alert alert-success">';
                        foreach ($msg_suc as $suc) {
                            echo "- " . $suc . "<br/>";
                        }
                    echo '</div>';
                }
                unset($_SESSION['msgerr']);
                unset($_SESSION['msgsuc']);
            ?>
             <div class="col-md-6 col-xs-6">
                <div class="alert alert-info">Số dư : <?= number_format($adminuser->balance)?> VND</div>
            </div>
            <div class="col-md-3 col-xs-3" style="float: right;">
                <div class="alert alert-info">UID : <?=$adminuser->getId()?>
                    <br>
                    Scret key : <?=$adminuser->scret_key?>
                    <br>
                    <a href="<?=$base_url?>/demo.add_phone.zip">Tải code demo</a>
                </div>
            </div>
          
        </div>
<div class="row">
            <div class="col-md-9 col-xs-9">
                <div class="page-header">
                    <h4>Lịch sử cộng trừ tiền</h4>
                </div>
                <div style="max-height:300px; overflow: auto">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Số tiền</th>
                                <th>Số dư trước</th>
                                <th>Số dư sau</th>
                                <th>Thời gian</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody id="load_data_histories">
                        
                        </tbody>
                    </table>
                    <div id="loading_data"></div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3">
                <div class="page-header">
                    <h4>Cộng tiền Account</h4>
                </div>
                <form method="post" action="#" id="faddbalance">
                    <div class="form-group">
                      <label for="userphoneadd" class="sr-only">Phone</label>
                      <input type="text" class="form-control" id="userphoneadd" name="userphoneadd" placeholder="SDT..." autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="money" class="sr-only">Money</label>
                        <input type="text" class="form-control" name="money" id="money" placeholder="Số tiền... "/>
                    </div>
                    <div class="form-group" id="btn-congtien">
                        <label for="money" class="sr-only"></label>
                        <button type="submit" class="btn btn-primary">Cộng tiền</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
            if($adminuser->group_id == 1 || $adminuser->group_id == 2 ) {
        ?>
        <div class="row">
            <div class="col-md-9 col-xs-9">
                <div class="page-header">
                    <h4>Danh sách account</h4>
                    <div class="pull-right">
                        <select name="month" id="month" class="form-control">
                            <?php
                                $thang_cur = date('m');
                                for($i = 1; $i <= 12; $i++) {
                                    $selected = $thang_cur == $i ? 'selected' : '';
                            ?>
                                    <option value="<?= $i ?>" <?= $selected ?> >Tháng <?= $i ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="load_accounts" style="max-height:300px; overflow: auto">
                    
                </div>
            </div>
            <div class="col-md-3 col-xs-3">
                <div class="page-header">
                    <h4>Thêm account</h4>
                </div>
                <form method="post" action="#" id="fadduser" class="">
                    <div class="form-group">
                      <label for="name" class="sr-only">Tên</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Tên...">
                    </div>
                    <div class="form-group">
                      <label for="phone" class="sr-only">Phone</label>
                      <input type="text" class="form-control" id="userphone" name="userphone" placeholder="SDT..." autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu..."/>
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password 2</label>
                        <input type="password" class="form-control" name="trans_pass" id="trans_pass" placeholder="Mật khẩu giao dịch..."/>
                    </div>
  
                     <div class="form-group">
      <select class="form-control"  name="group_id" id="group_id">
        <option value="0">Chọn quyền đại lí</option>
                     <?php  $models_gr = new Models_Groupz();
     $grs = $models_gr->customFilter('',array('id'=>array($adminuser->group_id, '>')),'id asc');
  ?>
     <?php foreach ($grs as $gr): ?>
        <option value="<?=$gr->getId()?>"><?=$gr->name?></option>
     <?php endforeach ?>
      </select>
                    </div>

                    <div class="form-group" id="btn-addacc">
                        <label for="password" class="sr-only"></label>
                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>     
                </form>
            </div>
        </div>

        
        <?php
            }
            ?>

        <div class="page-header">
            <h4>Thêm mới SDT cần thanh toán <small>Tối thiểu 50,000 VND.</small></h4>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <form method="post" action="#" id="faddphone" class="form-inline">
                    <div class="form-group">
                      <label for="phone" class="sr-only">Số điện thoại</label>
                      <input type="text" class="form-control" id="phone" name="phone" placeholder="SDT...">
                    </div>
                    <div class="form-group">
                        <label for="type" class="sr-only"></label>
                        <select class="form-control" name="loai" id="loai">
                            <option value="1">Viettel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type" class="sr-only">Loại</label>
                        <select class="form-control" name="type" id="type">
                            <option value="0">Trả sau</option>
                            <option value="1">Trả trước</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gop" class="sr-only">Nạp gộp</label>
                        <select class="form-control" name="gop" id="gop">
                            <option value="1">Nạp gộp nhiều thẻ</option>
                            <option value="0">Không gộp thẻ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="canthanhtoan" class="sr-only">Cần thanh toán</label>
                        <input type="text" class="form-control" id="canthanhtoan" name="canthanhtoan" placeholder="Cần thanh toán...">
                    </div>
                    <div class="form-group" id="btn-addphone">
                        <label for="canthanhtoan" class="sr-only"></label>
                        <button type="submit" class="btn btn-primary">Thêm Mới</button>
                    </div>
                  </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-4">
                <div class="page-header">
                    <h4>Upload File</h4>
                </div>
                <form method="post" enctype="multipart/form-data" class="form-inline" action="process_file.php">
                    <div class="form-group">
                        <label class="control-label">Select File</label>
                        <input type="file" class="file" name="fileToUpload">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Upload</button>
                    <a href='demouploadfile.xlsx'>Download File Mẫu</a>
                    <br/>Chú ý cột "Loai thue bao", các giá trị sẽ là :
                    <br/>Trả sau : 0, Trả trước : 1
                    <br/>Cột "Nạp Gộp" có 2 giá trị : 0 là không gộp nhiều thẻ, 1 là nạp gộp nhiều thẻ.
                </form>
            </div>
            <div class="col-md-8 col-xs-8">
                <div class="page-header">
                    <h4>Tìm kiếm</h4>
                </div>
                <div class="row">
                    <form id="fsearch">
                        <div class='col-md-3'>
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control" name="start_date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' class="form-control" name="end_date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option value=0> -- Tất cả --</option>
                                    <option value=1> -- Hoàn thành --</option>
                                    <option value=2> -- Đang xử lý --</option>
                                    <option value=3> -- Lỗi xử lý --</option>
                                    <option value=4> -- Bị khoá --</option>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-2' id="btn-search">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker1').datetimepicker({
                                    format : 'DD/MM/YYYY',
                                });
                                $('#datetimepicker2').datetimepicker({
                                    useCurrent: false,
                                    format : 'DD/MM/YYYY',
                                });
                                $("#datetimepicker1").on("dp.change", function (e) {
                                    $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
                                });
                                $("#datetimepicker2").on("dp.change", function (e) {
                                    $('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
                                });
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
        <div class="page-header">
            <h4>Danh sách số điện thoại cần thanh toán</h4>
        </div>
        <div class="alert alert-danger">
                Chú ý : Nếu Thuê bao không thể được xử lý thì có thể thuê bao chưa đăng ký thanh toán bằng thẻ cào!<br/>
                Với thuê bao trả sau Viettel: Khách hàng cần đăng ký thanh toán cước bằng thẻ cào, soạn DK TT gửi 166
            </div>
        <div class="row">
            <div class="col-xs-12 col-md-12" id="load_data_phone">
                
            </div>
        </div>
    </div>
    
    <script>
        $(function() {
            // load acc
            loadAcc();
            
            $('#month').change(function() {
                loadAcc();
            });
            
            // load data history
            $.ajax({
                url : '/ajax/load_history.php',
                beforeSend : function() {
                    $('#loading_data').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                error : function() {
                    $("#loading_data").html('Loading data lỗi, Vui lòng refresh lại trang!');
                },
                success : function(data) {
                    $("#load_data_histories").html(data);
                },
                complete : function() {
                    $("#loading_data").html('');
                }
            });
            
            // load data phone
            $.ajax({
                url : '/ajax/load_phones.php',
                beforeSend : function() {
                    $('#load_data_phone').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                error : function() {
                    $("#load_data_phone").html('Loading data lỗi, Vui lòng refresh lại trang!');
                },
                success : function(data) {
                    $("#load_data_phone").html(data);
                }
            });
            
            // xu ly form
            $("#fsearch").ajaxForm({
                url: '/ajax/load_phones.php',
                beforeSubmit : function() {
                    $('#btn-search').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                success: function(data) {        
                    $("#load_data_phone").html(data);
                },
                complete : function() {
                    $("#btn-search").html('<button type="submit" class="btn btn-primary">Search</button>');
                }
            });
            
            // xu ly form
     
            $('#btn-addphone').click(function(e) {
    e.preventDefault();
    var trans_pass = prompt("Nhập mật khẩu giao dịch");
if (trans_pass!=""&&trans_pass!=null)
{
    $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/confirmTransPass.php',
                                data : {
                                    pass : trans_pass
                                },
                               
                                success: function(data) {        
                                   if (data.code==1) {
                                alert(data.msg);
                                   }else{
             $("#faddphone").ajaxSubmit({
                  dataType : 'json',
                  url: '/ajax/process.php',
                  beforeSubmit : function() {
                      $("#btn-addphone").html('Loading...');
                  },
                  success: function(data) {        
                      alert(data.msg);
                      location.reload();
                  }
            });
                                   }
                                }
                            });
                }

});
            // xu ly form
            $("#fadduser").ajaxForm({
                  dataType : 'json',
                  url: '/ajax/adduser.php',
                  beforeSubmit : function() {
                      $("#btn-addacc").html('Loading...');
                  },
                  success: function(data) {        
                      alert(data.msg);
                      location.reload();
                  }
            });
            
            // xu ly form
            $('#btn-congtien').click(function(e) {
    e.preventDefault();
    var trans_pass = prompt("Nhập mật khẩu giao dịch");
if (trans_pass!=""&&trans_pass!=null)
{
    $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/confirmTransPass.php',
                                data : {
                                    pass : trans_pass
                                },
                               
                                success: function(data) {        
                                   if (data.code==1) {
                                alert(data.msg);
                                   }else{
                                 $("#faddbalance").ajaxSubmit({
                  dataType : 'json',
                  url: '/ajax/addmoney.php',
                  beforeSubmit : function() {
                      $("#btn-congtien").html('Loading...');
                  },
                  success: function(data) {        
                      alert(data.msg);
                      location.reload();
                  }
            });
                                   }
                                }
                            });
                }

});
       
        });
        
        function loadAcc() {
            $.ajax({
                url : '/ajax/load_accounts.php',
                data : {
                    month : $('#month').val(),
                },
                beforeSend : function() {
                    $('#load_accounts').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                error : function() {
                    $("#load_accounts").html('Loading data lỗi, Vui lòng refresh lại trang!');
                },
                success : function(data) {
                    $("#load_accounts").html(data);
                    // xu ly form
                    $(".btn-delete").click(function(){
                        if(confirm('Bạn chắc chắn muốn xoá User này?')) {
                                                            var trans_pass = prompt("Nhập mật khẩu giao dịch");
if (trans_pass!=""&&trans_pass!=null)
{
    var userId=$(this).attr('data');
    $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/confirmTransPass.php',
                                data : {
                                    pass : trans_pass
                                },
                               
                                success: function(data) {        
                                   if (data.code==1) {
                                alert(data.msg);
                                   }else{
                                                                $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/deluser.php',
                                context : $(this),
                                data : {
                                    userId : userId
                                },
                                beforeSend : function() {
                                    $(this).parent().html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                                },
                                success: function(data) {        
                                    loadAcc();
                                    alert(data.msg);

                                }
                            });
                                   }
                                }
                            });
                }
           
                        }
                    });
              $(".btn-blockz").click(function(){
                        if(confirm('Bạn chắc chắn muốn '+$(this).html()+' User này?')) {
                            $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/blockuser.php',
                                context : $(this),
                                data : {
                                    userId : $(this).attr('data')
                                },
                                beforeSend : function() {
                                    $(this).parent().html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                                },
                                success: function(data) {        
                                    if(data.code!= 0) {
                                        loadAcc();
                                    }
                                    alert(data.msg);

                                }
                            });
                        }
                    });

                    // xu ly form
                    $(".btn-reset").click(function(){
                        if(confirm('Bạn chắc chắn muốn Reset User này?')) {
                                var trans_pass = prompt("Nhập mật khẩu giao dịch");
if (trans_pass!=""&&trans_pass!=null)
{
var userId=$(this).attr('data');
    $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/confirmTransPass.php',
                                data : {
                                    pass : trans_pass
                                },
                               
                                success: function(data) {        
                                   if (data.code==1) {
                                alert(data.msg);
                                   }else{
                                                $.ajax({
                                dataType : 'json',
                                type : 'POST',
                                url: '/ajax/reset.php',
                                data : {
                                    userId : userId
                                },
                                beforeSend : function() {
                                    $(this).parent().html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                                },
                                success: function(data) {        
                                    loadAcc();
                                    alert(data.msg);

                                }
                            });
                                   }
                                }
                            });
                }
                        }
                    });
                }
            });
        }

        function addScript(src,callback) {
            var s = document.createElement( 'script' );
            s.setAttribute( 'src', src );
            s.onload=callback;
            document.body.appendChild( s );
        }
      
        function startPhone(id) {
            if(confirm("Bạn chắc chắn muốn Bật thanh toán cho SDT này?")){
                $.ajax({
                    url : '/ajax/start.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                            $("#fsearch").submit();
                        }
                    }
                });
            }
        }
      
        function pausePhone(id) {
            if(confirm("Bạn chắc chắn muốn dừng thanh toán cho SDT này?")){
                $.ajax({
                    url : '/ajax/pause.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                            $("#fsearch").submit();
                        }
                    }
                });
            }
        }
        
        function refund(id) {
            if(confirm("Bạn chắc chắn muốn hoàn tiền cho SDT này?")){
                $.ajax({
                    url : '/ajax/refund.php',
                    type : 'post',
                    dataType : 'json',
                    data : {
                        id : id
                    },
                    success : function(data) {
                        alert(data.msg);
                        if(data.code === 0) {
                            $("#fsearch").submit();
                        }
                    }
                });
            }
        }
        
        function detail(id) {
            location.href = 'detail.php?phone_id=' + id;
        }
    </script>   
</body>
</html>
