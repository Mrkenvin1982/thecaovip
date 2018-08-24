<?php
ini_set('display_errors', 1);
$uid= 6; //vui lòng thay UID của bạn trong trang Admin System
$phone_num='01234567890';
$card_type =1; 	//1: viettel, 2: mobi, 3: vina
$trans_type =0; 	//0: trả trước, 1: trả sau
$scret_key = '4dfgd2dbdc5gd21f281f38ed09'; //vui lòng thay scret key của bạn trong trang Admin System
$is_sum = 1; //0: nạp gộp nhiều thẻ, 1: không gộp thẻ
$money = 100000; //số tiền cần nộp
$public_key = hash('sha256', $uid . $phone_num . $card_type . $trans_type.$is_sum.$money.$scret_key);//public key
$api_link = "http://thecaovip.com/api/phones/add.php?uid=$uid&phone_num=$phone_num&card_type=$card_type&trans_type=$trans_type&is_sum=$is_sum&money=$money&key=$public_key"; // api link có thể dùng method post or get;
$result = file_get_contents("$api_link");
$result = json_decode($result);
print_r($result);
