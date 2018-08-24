<?php 
include 'config.php';

if(!isset($adminuser)||!is_object($adminuser)) {
if (!isset($page)||$page!='login') {
    header('location:login.php');
    exit();
}
}elseif (is_object($adminuser)&&$page=='login') {
    header('location:index.php');
    exit(); 
}
 ?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Thẻ Cào VIP</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
      <!-- <link href="./node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/> -->
<link href="/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="/css/jquery-ui.min.css" rel="stylesheet"/>

      
      <link href="css/style.css?v4" rel="stylesheet">

      <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <script type="text/javascript" src="./node_modules/jquery/dist/jquery.min.js"></script>
      <script type="text/javascript" src="/js/jquery-ui.min.js"></script>

      
      <script type="text/javascript" src="./node_modules/jquery-form/dist/jquery.form.min.js"></script>
      <script type="text/javascript" src="./node_modules/moment/min/moment.min.js"></script>
      <script type="text/javascript" src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="./node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script> -->
<script type="text/javascript" src="/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>


   </head>