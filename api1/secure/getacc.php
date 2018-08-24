<?php
include '../config.php';
$id = $_GET['id'];

$models_users = new Models_Users();
$users = $models_users->getObject(intval($id));

$_SESSION['admin_logged'] = $users;
 
header('location:' . $base_url);