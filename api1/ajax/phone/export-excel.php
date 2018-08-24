<?php 
include '../../config.php'; 
require_once '../../Classes/PHPExcel.php'; 



foreach($_GET as $key => $value) {
    $$key = trim($value);
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
   }
}
$models_user = new Models_Users();
    $checkid='';
if (isset($phone)) {
$uss = $models_user->getObjectByCondition('',array('phone'=>$phone));
if (is_object($uss)) {
  $checkid =" and userid = {$uss->getId()}";
}
}

 $cst='';
if ($status!=100) {
        if($status==99){
                     $cst= " and canthanhtoan =0 ";
                }
                elseif ($status==-1) {
                    $cst = " and canthanhtoan <0 ";
                }
                else {
                     $cst = " and (status = $status and canthanhtoan >0 ) ";

            
                }
}
$target=strtoupper($target);

if (substr($target, 0,1)=='T') {
$target = substr($target, 1);
$checkst= "id = '{$target}' and";
}else{
$target = substr($target, 1);
   $checkst= "(id LIKE '%{$target}%' or phone LIKE '%{$target}%') and";
}

$models_phones = new Models_Phones();
$arr_st = explode('/', trim($start_date));
$start_date = mktime(0,0,0,$arr_st[1],$arr_st[0],$arr_st[2]);

$arr_en = explode('/', trim($end_date));
$end_date = mktime(23,59,59,$arr_en[1],$arr_en[0],$arr_en[2]);
   $typecheck='';
   if (in_array($type, array(0,1,2))) {
      $typecheck= " and type =$type ";
   }elseif($type==10){
$typecheck= " and gop =0 ";
   }
   elseif($type==11){
$typecheck= " and gop =1 ";
   }
   $checkuser ='';
if ($on_me==1) {
   $checkuser =" and userid = {$adminuser->getId()}";
}

$query_total = "SELECT * FROM Phones WHERE $checkst time BETWEEN $start_date and $end_date  $checkid $cst $typecheck $checkuser order by id desc";
$result = $models_phones->customQuery($query_total);
		$objExcel = new PHPExcel;
	$objExcel->setActiveSheetIndex(0);
	$sheet = $objExcel->getActiveSheet()->setTitle('Danh sách khớp thẻ');

	$rowCount = 1;
	$sheet->setCellValue('A'.$rowCount,'Số thứ tự');
	$sheet->setCellValue('B'.$rowCount,'Mã đơn hàng');
	$sheet->setCellValue('C'.$rowCount,'SĐT/FTTH');
	$sheet->setCellValue('E'.$rowCount,'Thời gian');
	$sheet->setCellValue('E'.$rowCount,'Nhà mạng');
	$sheet->setCellValue('F'.$rowCount,'Loại');
	$sheet->setCellValue('G'.$rowCount,'Số tiền');
	$sheet->setCellValue('H'.$rowCount,'Gộp');
	$sheet->setCellValue('I'.$rowCount,'Đã nạp');
	$sheet->setCellValue('J'.$rowCount,'Trạng thái');



foreach ($result as $rs) {
		$rowCount++;
		$sheet->setCellValue('A'.$rowCount,$rowCount-1);
	$sheet->setCellValue('B'.$rowCount,'T'.$rs->getId());
	$sheet->setCellValue('C'.$rowCount,$rs->phone);
	$sheet->setCellValue('D'.$rowCount,date('H:i:s d/m/Y',$rs->time));
	switch ($rs->loai) {
		case 1:
		$provider = 'Viettel';
			break;
		case 2:
		$provider = 'Mobile';
			break;
		case 3:
		$provider = 'Vina';
			break;					
		default:
		$provider = 'không xác định';
			break;
	}
	$sheet->setCellValue('E'.$rowCount,$provider);

	$sheet->setCellValue('F'.$rowCount,$rs->type==0?'trả sau':'trả trước');
	$sheet->setCellValue('G'.$rowCount,number_format($rs->canthanhtoan));
	$sheet->setCellValue('H'.$rowCount,$rs->gop==0?'không':'có');
	$sheet->setCellValue('I'.$rowCount,number_format($rs->dathanhtoan));
	$suc_per = ($rs->dathanhtoan/$rs->canthanhtoan)*100; 
             if($rs->canthanhtoan <= 0){
                  $status="Hoàn thành";
                }
                else {
                    if($rs->status == 0){
                        $status="Không hoạt động";
                    }
                    elseif($rs->status == 1){
                        $status="Chờ xử lý";
                    }
                    elseif($rs->status == 2){
                         $status="Đang xử lí";
                    }
                    elseif($rs->status == 3){
                        $status="Lỗi";
                    }
                    elseif($rs->status == 4){
                        $status="Bị khóa";
                    }
                    elseif($rs->status == 5){
                         $status="Đã xóa";
                    }
                }
	$sheet->setCellValue('J'.$rowCount,$status);
}


	$objWriter = new PHPExcel_Writer_Excel2007($objExcel);
	$filename = '../phone/export.xlsx';
	$objWriter->save($filename);

	header('Content-Disposition: attachment; filename="khopthe.xlsx"');  
	header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');  
	header('Content-Length: ' . filesize($filename));  
	header('Content-Transfer-Encoding: binary');  
	header('Cache-Control: must-revalidate');  
	header('Pragma: no-cache');  
	readfile($filename);  
	return;
 ?>