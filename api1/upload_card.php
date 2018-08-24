<?php
include 'config.php';
include "Classes/PHPExcel.php";
$users = $_SESSION['admin_logged'];


if(!is_object($users)) {
    // chuyen huong login
    header('location:login.php');
    exit();
}

if(isset($_POST["submit"])) {
    $msg_err = [];
    $msg_suc = [];

 if (isset($_FILES["fileToUpload"])) {
     $file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));
 $expensions= array("xls","xlsx");
    if(in_array($file_ext,$expensions)=== false){
         $msg_err="Chỉ hỗ trợ file xls hoặc xlsx.";
         $_SESSION['msgec'] = $msg_err;
         $_SESSION['msgsc'] = $msg_suc;
header('location:/admin/modules/CardStores/danhsach.php');
exit;
      }
    $tmpfname = $_FILES["fileToUpload"]["tmp_name"];
    $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
    $excelObj = $excelReader->load($tmpfname);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $err = false;
    $sum = 0;
$models_card = new Models_CardStores();
    for ($row = 2; $row <= $lastRow; $row++) {
        $loaithe = $worksheet->getCell('A'.$row)->getValue();
        if(!in_array($loaithe, array(1,2,3))) {
            continue;
        }
        $seri = intval($worksheet->getCell('B'.$row)->getValue());
                if(empty($seri)) {
            continue;
        }

        $pin = $worksheet->getCell('C'.$row)->getValue();
                if(empty($pin)) {
            continue;
        }

        $menhgia = $worksheet->getCell('D'.$row)->getValue();
                        if(empty($menhgia)) {
            continue;
        }

        $menhgia =intval($menhgia);
        $seri=Library_String::encryptStr($seri,SERI_PASS);
        $pin=Library_String::encryptStr($pin,PIN_PASS);

        $ngayhethan = $worksheet->getCell('E'.$row)->getValue();

        $cards = new Persistents_CardStores();
        $cards->pin=$pin;
        $cards->seri=$seri;
        $cards->expire_date=$ngayhethan;
        $cards->price=$menhgia;
        $cards->card_type=$loaithe;
        $cards->time_in=time();
        $cards->orders=1;
        $cards->status=1;
/*        print_r($cards);
        echo "<br>";*/
$models_card->setPersistents($cards);
if ($models_card->add(1)) {

}
    }
  $msg_suc = "Thêm thẻ thành công";
 }
 else
 {

  $msg_err = 'Vui lòng chọn file';   
 }
}
else {
    $msg_err = 'Post fail';
}

$_SESSION['msgec'] = $msg_err;
$_SESSION['msgsc'] = $msg_suc;
header('location:/admin/modules/CardStores/danhsach.php');
