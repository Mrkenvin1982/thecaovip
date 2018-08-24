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
 $cst='';
if ($status!=100) 
   $cst="and status = $status";
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


$query_total = "SELECT * FROM Phones WHERE $checkst time BETWEEN $start_date and $end_date $cst order by id desc";

$result = $models_phones->customQuery($query_total);
		$objExcel = new PHPExcel;
	$objExcel->setActiveSheetIndex(0);
	$sheet = $objExcel->getActiveSheet()->setTitle('Danh sách khớp thẻ');

	$rowCount = 1;
	$sheet->setCellValue('A'.$rowCount,'Số thứ tự');
	$sheet->setCellValue('B'.$rowCount,'Mã đơn hàng');
	$sheet->setCellValue('C'.$rowCount,'Thời gian');
	$sheet->setCellValue('D'.$rowCount,'Nhà mạng');
	$sheet->setCellValue('E'.$rowCount,'Loại');
	$sheet->setCellValue('F'.$rowCount,'Số tiền');
	$sheet->setCellValue('G'.$rowCount,'Gộp');
	$sheet->setCellValue('H'.$rowCount,'Đã nạp');
	$sheet->setCellValue('I'.$rowCount,'Trạng thái');



foreach ($result as $rs) {
		$rowCount++;
		$sheet->setCellValue('A'.$rowCount,$rowCount-1);
	$sheet->setCellValue('B'.$rowCount,'T'.$rs->getId());
	$sheet->setCellValue('C'.$rowCount,date('h:i:s d/m/Y',$rs->time));
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
	$sheet->setCellValue('D'.$rowCount,$provider);

	$sheet->setCellValue('E'.$rowCount,$rs->type==0?'trả trước':'trả sau');
	$sheet->setCellValue('F'.$rowCount,number_format($rs->canthanhtoan));
	$sheet->setCellValue('G'.$rowCount,$rs->gop==0?'không':'có');
	$sheet->setCellValue('H'.$rowCount,number_format($rs->dathanhtoan));
	$suc_per = ($rs->dathanhtoan/$rs->canthanhtoan)*100; 
if ($suc_per==100) {
   $status= "Hoàn thành";
}elseif ($rs->canthanhtoan==0) {
  $status= "đã hủy"; 
}elseif ($rs->status==0) {
   $status= "Đang tạm dừng";
}else{
	 $status=($rs->dathanhtoan/$rs->canthanhtoan)*100 ."%";
}
	$sheet->setCellValue('I'.$rowCount,$status);
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