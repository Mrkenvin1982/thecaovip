<?php
include '../config.php';

// get post data
foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}

// log request
$file_log = "login_rq.log";
Library_Log::writeOpenTable($file_log);
Library_Log::writeHtml("Request : " . print_r($_POST, 1), $file_log);
Library_Log::writeCloseTable($file_log);

// check vailid phone
if(!Library_Validation::isPhoneNumber($phone)) {
    echo json_encode(array('code' => 1, 'msg' => 'SDT không hợp lệ! '.$phone));
    exit();
}

// get user by username
$models_users = new Models_Users();
$users = $models_users->getObjectByCondition('', array('phone' => $phone));

if(is_object($users) && $users->status == 1) {
    $salt = $users->salt;
    $password_true = $users->password;
    $password = hash('sha256', $password . $salt);
    
    $memcache = new Memcache;
    $memcache->connect('localhost', 11211) or die ("Could not connect");
    $solansai = intval($memcache->get(md5($phone)));
    $solansai = 0;
    
    if($solansai >= 5) {
        $users->status =2;
        $models_users->setPersistents($users);
        $models_users->edit(array('status'), 1);
        echo json_encode(array('code' => 1, 'msg' => 'Acc bị khoá!'));
    }
    else {
        if($password == $password_true) {

        
                echo json_encode(array('code' => 0, 'msg' => 'login thanh cong!'));
                $memcache->delete(md5($phone));
            // luu sesion 
            $_SESSION['admin_logged'] = $users;
        }
        else {
            $memcache->set(md5($phone), $solansai + 1);
            echo json_encode(array('code' => 1, 'msg' => 'Mật khẩu không đúng!'));
        }
    }
}
elseif (is_object($users) && $users->status == 2) {
  echo json_encode(array('code' => 1, 'msg' => 'Tài khoản đang bị khóa!'));  
}
else {
    echo json_encode(array('code' => 1, 'msg' => 'Tài khoản không tồn tại!'));
}