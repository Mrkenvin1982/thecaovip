<?php
    include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <style>
        .m-b-sm {
            margin-bottom: 10px;
        }

        .m-t-sm {
            margin-top: 10px;
        }
    </style>

    <script type="text/javascript" src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="http://123.17.153.33:30000/socket.io/socket.io.js"></script>
</head>
<body>
<button type="button" class="btn btn-sm btn-primary" id="btnon">ON</button> 
<button type="button" class="btn btn-sm btn-primary" id="btnoff">OFF</button> 
<div id="data"></div>
    <script>
        $(function() {
            var socket = io.connect('http://123.17.153.33:30000');
            socket.on('connect', function() {
                $("#data").html('Socket connected');
            });
            
            $("#btnon").click(function() {
                $("#data").html("");
                socket.emit('on');
            });
            
            $("#btnoff").click(function() {
                $("#data").html("");
                socket.emit('off');
            });
        });
    </script>
    
    <script>
        
        function deactive() {
            socket.emit('deactive', {port : $("#port").val()});
        }
    </script>   
</body>
</html>
