<?php
require_once 'config.php';
ini_set('display_errors', 1);
$controller = $_REQUEST['controller'];
$action     = $_REQUEST['action'];
$classname = 'Controllers_' . $controller;
$entity = $controller;
$controller = new $classname($entity);

$controller->execute($action);
$controller->redirect();