<?php 
include '../../config.php'; 
foreach($_POST as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
    }
}
if(!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}

                if ($adminuser->group_id==1) {
                      
              $models_phones = new Models_Phones();
              $phone = $models_phones->getObject($id);
              if (is_object($phone)&&$phone->canthanhtoan>0) {
 if ($phone->orders==0) {
                $phone->orders  =1;
               }else{
                 $phone->orders  =0;
               }
               $models_phones->setPersistents($phone);
               if ($models_phones->edit(array('orders'),1)) {
               	 echo json_encode(array('code' => 0, 'msg' => "Ưu tiên giao dịch T{$phone->getId()} thành công!"));
    exit();
               }

              }
                    }
                             	 echo json_encode(array('code' => 1, 'msg' => "Xử lí thất bại"));
    exit();