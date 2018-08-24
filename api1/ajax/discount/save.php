<?php 
include '../../config.php'; 

foreach($_POST as $key => $value) {
    if(!Library_Validation::antiSql($value)) {
        echo json_encode(array('code' => 1, 'msg' => 'Dữ liệu có vấn đề!'));
        exit();
   }
$$key=$value;
}


if(!is_object($adminuser)) {
    echo json_encode(array('code' => 1, 'msg' => 'Bạn chưa đăng nhập!'));
    exit();
}
$uid= intval(Library_String::decryptstr(base64_decode($acc), $key_enc)); //decode
$models_user = new Models_Users();
$user = $models_user->getObject($uid);

if (!is_object($user)) {
	    echo json_encode(array('code' => 1, 'msg' => 'Người dùng này không tồn tại'));
    exit();
}
if ($adminuser->group_id!=1&&$adminuser->getId()!=$user->refer) {
	   echo json_encode(array('code' => 1, 'msg' => 'Bạn không đủ quyền'));
    exit();
}

$models_discount = new Models_DiscountPercentage();
$models_default = new Models_DefaultDiscount();
$minPercent =5;
if ($adminuser->group_id!=1) {

		$fuser_discount = $models_discount->getObjectByCondition('',array('user_id'=>$adminuser->getId()));
		if (!is_object($fuser_discount)) {
		$default_discount = $models_default->getLastObject()->discount;
$fuser_viettel_dis =$fuser_mobi_dis=$fuser_vina_dis=$fuser_ftth_dis=$default_discount;
$fuser_viettel_dis_min_chang =$fuser_mobi_dis_min_chang=$fuser_vina_dis_min_chang=$fuser_ftth_dis_min_chang=$default_discount-$minPercent;

		}else{
			$fuser_viettel_dis = $fuser_discount->viettel_percent;
			$fuser_mobi_dis = $fuser_discount->mobi_percent;
			$fuser_vina_dis = $fuser_discount->vina_percent;
			$fuser_ftth_dis = $fuser_discount->ftth_percent;

			$fuser_viettel_dis_min_chang = $fuser_discount->viettel_percent-$minPercent;
			$fuser_mobi_dis_min_chang = $fuser_discount->mobi_percent-$minPercent;
			$fuser_vina_dis_min_chang = $fuser_discount->vina_percent-$minPercent;
			$fuser_ftth_dis_min_chang = $fuser_discount->ftth_percent-$minPercent;

		}

			if ($viettel_percent>$fuser_viettel_dis) {
					echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ viettel của bạn là: ".$fuser_viettel_dis."% không thể set chiết khấu cao hơn của bạn!" ));
    	exit();
			}
					if ($mobi_percent>$fuser_mobi_dis) {
					echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ mobi của bạn là: ".$fuser_mobi_dis."% không thể set chiết khấu cao hơn của bạn!" ));
    	exit();
			}
							if ($vina_percent>$fuser_vina_dis) {
					echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ vina của bạn là: ".$fuser_vina_dis."% không thể set chiết khấu cao hơn của bạn!" ));
    	exit();
			}
										if ($ftth_percent>$fuser_ftth_dis) {
					echo json_encode(array('code' => 1, 'msg' => "Chiết khấu ftth của bạn là: ".$fuser_ftth_dis."% không thể set chiết khấu cao hơn của bạn!" ));
    	exit();
			}


			if ($viettel_percent<$fuser_viettel_dis_min_chang) {
					echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ viettel của bạn là: ".$fuser_viettel_dis."% không thể set chiết khấu của tài khoản cấp 2 thấp hơn của bạn quá $minPercent%!" ));
    	exit();
			}
					if ($mobi_percent<$fuser_mobi_dis_min_chang) {
					echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ mobi của bạn là: ".$fuser_mobi_dis."% không thể set chiết khấu của tài khoản cấp 2 thấp hơn của bạn quá $minPercent%!" ));
    	exit();
			}
							if ($vina_percent<$fuser_vina_dis_min_chang) {
					echo json_encode(array('code' => 1, 'msg' => "Chiết khấu thẻ vina của bạn là: ".$fuser_vina_dis."% không thể set chiết khấu của tài khoản cấp 2 thấp hơn của bạn quá $minPercent%!" ));
    	exit();
			}
										if ($ftth_percent<$fuser_ftth_dis_min_chang) {
					echo json_encode(array('code' => 1, 'msg' => "Chiết khấu ftth của bạn là: ".$fuser_ftth_dis."% không thể set chiết khấu của tài khoản cấp 2 thấp hơn của bạn quá $minPercent%!" ));
    	exit();
			}

}
	

$checkis = $models_discount->getObjectByCondition('',array('user_id'=>$user->getId()));
if (is_object($checkis)) {
		$checkis->viettel_percent=$viettel_percent;
		$checkis->mobi_percent=$mobi_percent;
		$checkis->vina_percent=$vina_percent;
	$checkis->ftth_percent=$ftth_percent;
		$models_discount->setPersistents($checkis);
		$models_discount->edit(array('viettel_percent','mobi_percent','vina_percent','ftth_percent'),1);
	}else{
		$per_dis = new Persistents_DiscountPercentage();
	$per_dis->user_id=$user->getId();
	$per_dis->viettel_percent=$viettel_percent;
	$per_dis->mobi_percent=$mobi_percent;
	$per_dis->vina_percent=$vina_percent;
	$per_dis->ftth_percent=$ftth_percent;
	$per_dis->orders=1;
	$per_dis->status=1;
	$models_discount->setPersistents($per_dis);
$models_discount->add();
	
	}	



	   echo json_encode(array('code' => 0, 'msg' => 'Lưu chiết khấu thành công'));
	   exit;


 ?>