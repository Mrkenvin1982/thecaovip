<?php
$salt = md5(rand(0, 10000000000000));
$password = hash('sha256', '12345678' . $salt);

echo $salt;
echo '---';
echo $password;