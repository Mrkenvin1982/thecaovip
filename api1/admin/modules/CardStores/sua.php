<?php $page = 'cardsS' ?>
<?php require_once '../../../config.php'; ?>

<?php include('../../includes/cn-head.php');  ?>
<?php include('../../includes/cn-sidbar.php');  ?>
<?php include('../../includes/construct.php');  ?>
	<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-10">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Sửa thẻ</font></font></h2>
   </div>
   <div class="col-lg-2">
   </div>
</div>
<div class="ibox-content">
	<form method="post" action="/router.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
                <div class="table-responsive">
                <table class="admintable table">
                <tr>
                  <td class="key" width="10%">Mã thẻ: </td>
                  <td width="90%">
                  <input type="text" size="130" name="pin" style="width: 100%;" value="<?= isset($dan) ? Library_String::decryptstr($obj->pin,PIN_PASS) : '' ?>"/>
                  </td>
                </tr>
                                <tr>
                  <td class="key" width="10%">Seri: </td>
                  <td width="90%">
                  <input type="text" size="130" name="seri" style="width: 100%;" value="<?= isset($dan) ? Library_String::decryptstr($obj->seri,SERI_PASS) : '' ?>"/>
                  </td>
                </tr>
                                   <tr>
                  <td class="key" width="10%">Hạn sử dụng: </td>
                  <td width="90%">
                  <input type="text" class="form-control" value="<?= isset($dan) ? $obj->expire_date : '' ?>" name="expire_date" id="expire_date">
                  </td>
                </tr>
                    </tr>
                                <tr>
                  <td class="key" width="10%">Mệnh giá: </td>
                  <td width="90%">
                  <input type="number" size="130" name="price" style="width: 100%;" value="<?= isset($dan) ? $obj->price : '' ?>"/>
                  </td>
                </tr>
               <tr>
                  <td class="key" width="10%">Nhà mạng</td>
                  <td width="90%">
                  	<select class="form-control" name="card_type">
    <option value="1" <?= isset($dan) ? $obj->type==1?' selected':'' : 'selected' ?>>Viettel</option>
    <option value="2" <?= isset($dan) ? $obj->type==2?' selected':'' : '' ?>>Mobi</option>
    <option value="3" <?= isset($dan) ? $obj->type==3?' selected':'' : '' ?>>Vina</option>

  </select>
                  </td>
                </tr>
                
<tr>
                        <td class="key" width="10%">Trạng thái</td>
                        <td>
                            <input type="radio" name="status" value="0"<?= isset($dan) ? $obj->status==0?' checked':'' : '' ?>>&nbsp;Đã sử dụng
                            &emsp;
                            <input type="radio" name="status" value="1"<?= isset($dan) ? $obj->status==1?' checked':'' : '' ?>>&nbsp;Chưa sử dụng
                            &emsp;
                            <input type="radio" name="status" value="2"<?= isset($dan) ? $obj->status==2?' checked':'' : '' ?>>&nbsp;Thẻ sai!
                            &emsp;
                            <input type="radio" name="status" value="3"<?= isset($dan) ? $obj->status==3?' checked':'' : '' ?>>&nbsp;Thẻ lỗi!
                            &emsp;
                             <input type="radio" name="status" value="4"<?= isset($dan) ? $obj->status==4?' checked':'' : '' ?>>&nbsp;Đang xử lí!
                            &emsp;
                            
                        </td>
</tr>
               
                </table>
                </div>
                <button type="submit" class="btn btn-default" name="ok">Lưu</button>
				<input type="hidden" name="controller" value="<?= $entity ?>">
				<input type="hidden" name="id" value="<?= isset($dan) ? $obj->getId() : '' ?>">
				<input type="hidden" name="action" value="save">
                </form>
</div>
<?php include('../../includes/cn-footer.php'); ?>