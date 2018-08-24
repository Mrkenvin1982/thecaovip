<?php
include 'config.php';

unset($_SESSION['admin_logged']);
header('location:login.php');