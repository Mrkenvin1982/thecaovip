<?php
include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>

    <script type="text/javascript" src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="../node_modules/jquery-form/dist/jquery.form.min.js"></script>
    <script type="text/javascript" src="../node_modules/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>Admin System <small><?= $adminuser->name ?> ( <?= date('m/Y') ?> )</small></h1>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-6">
                <div class="page-header">
                    <h4>Tìm kiếm</h4>
                    <h4></h4>
                </div>
                <div class="row">
                    <form id="fsearch">
                        <div class='col-md-5'>
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control" value="<?=date('d/m/Y')?>" name="start_date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-5'>
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' class="form-control" value="<?=date('d/m/Y')?>" name="end_date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-2' >
                            <div class="form-group">
                                
                                    <select name="type">
                                        <option value="0">Tất cả</option>
                                        <option value="1">Thành công</option>
                                        <option value="2">Thất bại</option>

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
        <div class="row">
            <div class="col-md-12 col-xs-12" id="data">
                
            </div>
        </div>
    </div>
    <script>
        //$("#fsearch").submit();
        // xu ly form
        $("#fsearch").ajaxForm({
            url: 'load_log2.php',
            beforeSubmit : function() {
                $('#btn-search').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
            },
            success: function(data) {        
                $("#data").html(data);
            },
            complete : function() {
                $("#btn-search").html('<button type="submit" class="btn btn-primary">Search</button>');
            }
        });
        
        $("#fsearch").submit();
    </script>
</body>
</html>
