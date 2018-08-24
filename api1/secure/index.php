<?php
    include '../config.php';
    
    $models_users = new Models_Users();
    $list_users = $models_users->getList();
    $result = file_get_contents('http://27.72.144.82:30000/api/listports');
    $result = json_decode($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <style>
        .m-b-sm {
            margin-bottom: 10px;
        }

        .m-t-sm {
            margin-top: 10px;
        }
    </style>

    <script type="text/javascript" src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="../node_modules/jquery-form/dist/jquery.form.min.js"></script>
    <script type="text/javascript" src="../node_modules/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>Admin System <small>Khiem Tran Van</small></h1>
        </div>
        <div class="row">
            <div class="col-xs-8 col-md-8">
                <div class="row m-b-sm m-t-sm">
                    <div class="col-xs-12 col-md-12">
                        <div class="alert alert-danger">Trạng thái hệ thống : <span id="status"></span></div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="alert alert-warning">Trạng thái Show Hide : <span id="showhide"></span></div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="alert alert-info">Trạng thái Price : <span id="price"></span></div>
                    </div>
                </div>
                <div class="row m-b-sm m-t-sm">
                    <div class="col-md-2">
                        <select class="form-control m-b input-sm pull-right" id="port">
                            <?php
                                $i = 0;
                                foreach ($result->list as $obj) {
                            ?>                 
                                    <option value="<?= $i++ ?>"><?= $obj ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" placeholder="Command..." class="input-sm form-control" id="cm"> 
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-sm btn-primary" onclick="go()"> Go!</button> 
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger btn-sm" id="btnstatus">Status</button>
                        <button type="button" class="btn btn-warning btn-sm" id="btnshowhide">Show/hide</button>
                        <button type="button" class="btn btn-info btn-sm" id="btnprice">Price</button>
                    </div>
                </div>
                <div class="row m-b-sm m-t-sm">
                    <div class="col-md-2">
                        <input type="text" id="phone" value="" placeholder="Phone..." class="form-control input-sm"/>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" placeholder="SMS content..." class="input-sm form-control" id="msg"> 
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-sm btn-primary" onclick="sendsms()"> Send SMS</button> 
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-sm btn-primary" onclick="readAll()"> Read All SMS</button> 
                        <button type="button" class="btn btn-sm btn-primary" onclick="delAllSms()"> Delete ALL SMS</button> 
                    </div>
                </div>
                <div class="row m-b-sm m-t-sm">
                    <div class="col-xs-4 col-md-4">
                        <button type="button" class="btn btn-info btn-sm" onclick="deactive()">On-Off Port</button>
                        <hr/>
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>Port</td>
                                <td id="portName"></td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td id="portPhone"></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td id="portStatus"></td>
                            </tr>
                            <tr>
                                <td>Fail</td>
                                <td id="portFail"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-8 col-md-8">
                        <div id="data" style="border:1px solid gray; height: 300px; padding: 15px; overflow: auto"></div>
                    </div>
                </div>
            </div>
            <div class="col-xs-4 col-md-4" style="height:600px; overflow:auto">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="10">#</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                            $models_logs = new Models_Logs();
                            // get list file
                            $dir = '/home/khiemtv/www/sim/logs';
                            if(is_dir($dir)) {
                                if($dh = opendir($dir)) {
                                    while(($file_name = readdir($dh)) !== false) {
                                        if($file_name != "." && $file_name != "..") {
                                            // check exist
                                            $logs = $models_logs->getObjectByCondition('id', array('name' => $file_name));
                                            if(!is_object($logs)) {
                                                // insert
                                                $logs = new Persistents_Logs();
                                                $logs->setName($file_name);
                                                $logs->setStatus(1);
                                                $models_logs->setPersistents($logs);
                                                $models_logs->add();
                                            }
                                        }
                                    }
                                    closedir($dh);
                                }
                            }
                            $listlogs = $models_logs->getList();
                            $stt = 1;
                            foreach($listlogs as $obj) {
                                $link = 'read.php?file=' . $obj->getName();
                                $link2 = 'dellog.php?file=' . $obj->getName();
                                ?>
                                    <tr>
                                        <td><?php echo $stt++; ?></td>
                                        <td>
                                            <a target="_blank" href="<?= $link ?>"><?= $obj->getName() ?></a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?= $link2 ?>">Del</a>
                                        </td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="page-header">
            <h4>Danh sách số điện thoại cần thanh toán</h4>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="page-header">
                    <h4>Tìm kiếm</h4>
                </div>
                <div class="row">
                    <form id="fsearch">
                        <div class='col-md-2'>
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control" name="start_date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-2'>
                            <div class="form-group">
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' class="form-control" name="end_date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option value=0> -- Đang dừng --</option>
                                    <option value=1> -- Chờ xử lý --</option>
                                    <option value=2> -- Đang xử lý --</option>
                                    <option value=3> -- Lỗi xử lý --</option>
                                    <option value=4> -- Bị khoá --</option>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <select class="form-control" name="user_id">
                                <option value="0"> -- Chọn User -- </option>
                                <?php
                                    foreach ($list_users as $users) {
                                        echo "<option value='{$users->getId()}'> {$users->name} </option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class='col-md-2' id="btn-search">
                            <button type="submit" class="btn btn-primary" id="btn-search">Search</button>
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
            <div class="col-xs-12 col-md-12">
                <div id="loadphone"></div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $("#port").trigger("change");
            
            // xu ly form
            $("#fsearch").ajaxForm({
                url: 'loadphone.php',
                beforeSubmit : function() {
                    $('#btn-search').html("Xin mời chờ...<img src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
                },
                success: function(data) {        
                    $("#loadphone").html(data);
                    
                    $(".pause").click(function() {
                        $.ajax({
                            url : '../ajax/pause.php',
                            type : 'post',
                            dataType : 'json',
                            data : {
                                id : $(this).attr('data')
                            },
                            success : function(data) {
                                alert(data.msg);
                                $("#fsearch").submit();
                            }
                        });
                    });
                    
                    $(".del").click(function() {
                        $.ajax({
                            url : 'delete.php',
                            type : 'post',
                            dataType : 'json',
                            data : {
                                id : $(this).attr('data')
                            },
                            success : function(data) {
                                alert(data.msg);
                                $("#fsearch").submit();
                            }
                        });
                    });
                    
                    $(".start").click(function() {
                        $.ajax({
                            url : 'start.php',
                            type : 'post',
                            dataType : 'json',
                            data : {
                                id : $(this).attr('data')
                            },
                            success : function(data) {
                                alert(data.msg);
                                $("#fsearch").submit();
                            }
                        });
                    });
                },
                complete : function() {
                    $("#btn-search").html('<button type="submit" class="btn btn-primary">Search</button>');
                }
            });
            
            // load phone
            $("#fsearch").submit();
            
            // check maintian
            /*$.ajax({
                url: "http://8pay.vn/secure/checkmaintain.php",
                context: document.body,
                timeout : 3000,
                error: function(jqXHR, exception) {
                    console.log(jqXHR);
                    $("#status").html('Không xác định - Link die!');
                },
                success: function(data) {
                    $("#status").html(data);
                }
            });
            
            // check showhide
            $.ajax({
                url: "http://8pay.vn/secure/checkshowhide.php",
                context: document.body,
                timeout : 3000,
                error: function(jqXHR, exception) {
                    console.log(jqXHR);
                    $("#showhide").html('Không xác định - Link die!');
                },
                success: function(data) {
                    $("#showhide").html(data);
                }
            });
            
            // check price
            $.ajax({
                url: "http://8pay.vn/secure/checkprice.php",
                context: document.body,
                timeout : 3000,
                error: function(jqXHR, exception) {
                    console.log(jqXHR);
                    $("#price").html('Không xác định - Link die!');
                },
                success: function(data) {
                    $("#price").html(data);
                }
            });*/
            
            $("#btnstatus").click(function() {
                $.ajax({
                    url: "http://8pay.vn/secure/maintain.php",
                    context: document.body,
                    timeout : 3000,
                    error: function(jqXHR, exception) {
                        console.log(jqXHR);
                        $("#status").html('Không xác định - Link die!');
                    },
                    success: function(data) {
                        $("#status").html(data);
                    },
                    complete : function() {
                        alert('ok');
                    }
                });
            });
            
            $("#btnshowhide").click(function() {
                $.ajax({
                    url: "http://8pay.vn/secure/showhide.php",
                    context: document.body,
                    timeout : 3000,
                    error: function(jqXHR, exception) {
                        console.log(jqXHR);
                        $("#showhide").html('Không xác định - Link die!');
                    },
                    success: function(data) {
                        $("#showhide").html(data);
                    },
                    complete : function() {
                        alert('ok');
                    }
                });
            });
            
            $("#btnprice").click(function() {
                $.ajax({
                    url: "http://8pay.vn/secure/price.php",
                    context: document.body,
                    timeout : 3000,
                    error: function(jqXHR, exception) {
                        console.log(jqXHR);
                        $("#price").html('Không xác định - Link die!');
                    },
                    success: function(data) {
                        $("#price").html(data);
                    },
                    complete : function() {
                        alert('ok');
                    }
                });
            });
        
        });
    </script>
    <script>
        /*var socket = io.connect('http://apisms2.ddns.net:30000');
        socket.on('connect', function() {
            $("#data").html('Socket connected');
        });
      
        // cho viec kiem tra port sim
        socket.on('res', function(data) {
            $("#data").append(data + "<br/>");
        });
        
        socket.on('info', function(data) {
            $("#portName").html(data.port.path);
            $("#portPhone").html(data.phone);
            if(data.status === 0) {
                $("#portStatus").html('Active');
            }
            else if(data.status === 1) {
                $("#portStatus").html('Busy');
            }
            else {
                $("#portStatus").html('Maintain!');
            }
            $("#portFail").html(data.fail);
        });
        
        // cho viec thong bao nap the
        socket.on('phone_process', function(data) {
            $("#" + data.phone).prepend(data.msg + "<br/>");
        });

        socket.on('phone_status', function(data) {
            $("#status_" + data.phone).html(data.msg);
        });
        
        socket.on('phone_tt', function(data) {
            var cantt = parseInt($("#cantt_" + data.phone).html());
            var datt = parseInt($("#datt_" + data.phone).html());
            cantt = cantt*1000 - data.msg*1
            datt = datt*1000 + data.msg*1;
            
            if(cantt === 0) {
                $("#cantt_" + data.phone).html(0);
            }
            else {
                $("#cantt_" + data.phone).html(cantt/1000 + ",000");
            }
            $("#datt_" + data.phone).html(datt/1000 + ",000");
        });
        
        $("#port").change(function() {
            socket.emit('status', {port : $("#port").val()});
        });
        
        function go() {
            $("#data").html("");
            socket.emit('sendcm', {port : $("#port").val(), msg : $("#cm").val()});
        }
        
        function deactive() {
            socket.emit('deactive', {port : $("#port").val()});
        }
        
        function readAll() {
            $("#data").html("");
            socket.emit('readall', {port : $("#port").val()});
        }
        
        function sendsms() {
            $("#data").html("");
            socket.emit('sendsms', {port : $("#port").val(), phone : $("#phone").val(), msg : $("#msg").val()});
        }
        
        function delAllSms() {
            socket.emit('delallsms', {port : $("#port").val()});
        }
        */
    </script>   
</body>
</html>