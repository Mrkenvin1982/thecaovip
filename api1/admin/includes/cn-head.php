<?php if (!isset($_SESSION['admin'])) {
   header("location: /admin/login.php");
}
//module
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
        $entity = $uri_segments[3];
        $model_class_name = "Models_" . $entity;
        $persistent_class_name = "Persistents_" . $entity;
?>
<!DOCTYPE>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>THECAOVIP Admin</title>

    <link href="<?= $base_url ?>/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/admin/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="<?= $base_url ?>/admin/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <!-- Gritter -->
    <link href="<?= $base_url ?>/css/jquery-ui.min.css" rel="stylesheet"/>
    <link href="<?= $base_url ?>/admin/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="<?= $base_url ?>/admin/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/admin/css/animate.css" rel="stylesheet">
    <link href="<?= $base_url ?>/admin/css/style.css?v3" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
          <script type="text/javascript" src="<?= $base_url ?>/js/jquery-ui.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> 
    <script type="text/javascript" src="<?= $base_url ?>/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.9.2/full/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>
    
    
    
</head>

<body class="pace-done">
<div id="wrapper">