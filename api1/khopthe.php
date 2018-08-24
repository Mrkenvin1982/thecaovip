
<?php 
$page = 'khopthe';
include 'module/head.php';
include 'module/top-nav.php';


 ?>
       <div class="container">
   <div class="line"></div>
   <!-- uiView:  -->
   <div class="content">
      <!-- ngIf: discount != null -->
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
            <div class="col-md-12 col-xs-12">
                <div class="page-header">
                    <h4>Upload File</h4>
                </div>
                <form method="post" enctype="multipart/form-data" class="form-inline" action="process_file.php">
                    <div class="form-group">
                        <label class="control-label">Select File</label>
                        <input type="file" class="file" name="fileToUpload">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Upload</button>
                    <a href="demouploadfile.xlsx">Download File Mẫu</a>
                    <br>Chú ý cột "Loai thue bao", các giá trị sẽ là :
                    <br>Trả sau : 0, Trả trước : 1
                    <br>Cột "Nạp Gộp" có 2 giá trị : 0 là không gộp nhiều thẻ, 1 là nạp gộp nhiều thẻ.
                </form>
            </div>
         </div>
           <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="page-header">
                    <h4>Danh sách số điện thoại cần thanh toán</h4>
                </div>
                <div class="row">
                    <form id="fsearch">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker1">
                                    <input type="text" class="form-control" name="start_date">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker2">
                                    <input type="text" class="form-control" name="end_date">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option value="0"> -- Tất cả --</option>
                                    <option value="1"> -- Hoàn thành --</option>
                                    <option value="2"> -- Đang xử lý --</option>
                                    <option value="3"> -- Lỗi xử lý --</option>
                                    <option value="4"> -- Bị khoá --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" id="btn-search">
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

            <div class="row">
            <div class="col-xs-12 col-md-12" id="load_data_phone">
<</div>
        </div>

        <div class="alert alert-danger">
                Chú ý : Nếu Thuê bao không thể được xử lý thì có thể thuê bao chưa đăng ký thanh toán bằng thẻ cào!<br>
                Với thuê bao trả sau Viettel: Khách hàng cần đăng ký thanh toán cước bằng thẻ cào, soạn DK TT gửi 166
            </div>
      <!-- end ngIf: discount != null -->
   </div>
</div>
<script>
   
   $(function() {
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
   });
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
         <?php 
include 'module/footer.php';

          ?>
         <!-- ngIf: alert.alert == 1 -->
