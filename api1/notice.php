<?php include 'config.php';
$models_phones = new Models_Phones();
$keeps = $models_phones->customQuery("SELECT (SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 10000 AND 19999 and status =1)  'thẻ 10k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 20000 AND 29999 and status =1)  'thẻ 20k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 30000 AND 49999 and status =1) 'thẻ 30k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 50000 AND 99999 and status =1)  'thẻ 50k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 100000 AND 199999 and status =1)  'thẻ 100k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 200000 AND 299999 and status =1)  'thẻ 200k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 300000 AND 499999 and status =1)  'thẻ 300k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan BETWEEN 500000 AND 999999 and status =1)  'thẻ 500k',(SELECT COUNT(*) FROM Phones WHERE canthanhtoan >=1000000 and status =1)  'thẻ 1000k'")[0];
$rs_keep="";//<span style='font-weight:bold;color: blue'>Chúng tôi cần số lượng thẻ viettel như sau: <span>
foreach ($keeps as $key=> $val) {
  if (!in_array($key, array('id','phone','loai','type','userid','canthanhtoan','dathanhtoan','gop','last_balance','time','orders','status'))) {
   $rs_keep.= "<span style='font-weight:bold'>".$key."<span>:&nbsp;<span style='font-weight:bold;color:red'>".$val."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
  }
}



 ?>
<center><?=$rs_keep?></center>