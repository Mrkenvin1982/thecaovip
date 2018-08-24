<?php $page = 'users' ?>
<?php require_once '../../../config.php'; ?>

<?php include('../../includes/cn-head.php');  ?>
<?php include('../../includes/cn-sidbar.php');  ?>
<?php include('../../includes/construct.php');  ?>
	<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-10">
      <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Sửa Users</font></font></h2>
   </div>
   <div class="col-lg-2">
   </div>
</div>
<div class="ibox-content">
	<form method="post" action="/router.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
                <div class="table-responsive">
                <table class="admintable table">
                <tr>
                  <td class="key" width="10%">Số điện thoại: </td>
                  <td width="90%">
                  <input type="text" size="130" name="phone" style="width: 100%;" value="<?= isset($dan) ? $obj->phone : '' ?>"/>
                  </td>
                </tr>
               <tr>
                  <td class="key" width="10%">Nhà mạng</td>
                  <td width="90%">
                  	 <?php if (isset($dan)) {
                       switch ($obj->loai) {
                              case 1:
                               echo '<img src="/images/vtt.png" style="width:30px; min-width: 80px">';
                                 break;
                              case 2:
                               echo '<img src="/images/vms.png" style="width:30px; min-width: 80px">';
                                 break;
                              case 3:
                               echo '<img src="/images/vnp.png" style="width:30px; min-width: 80px">';
                                 break;
                           }
                     }else{
                       echo '<img src="/images/unknow.png" style="width:70%; min-width: 80px">';
                     } ?>
                  </td>
                </tr>
                <tr>
                        <td class="key" width="10%">Loại</td>
                        <td>
                            <input type="radio" name="type" value="1"<?= isset($dan) ? $obj->type==1?' checked':'' : '' ?>>&nbsp;Trả trước
                            <input type="radio" name="type" value="0"<?= isset($dan) ? $obj->type==0?' checked':'' : '' ?>>&nbsp;Trả sau
                        </td>
</tr>
<tr>
  <td class="key" width="10%">Tài khoản</td>
                  <td width="90%">
                    
                    <?php $models_user = new Models_Users();
                    echo $models_user->getObject($obj->userid)->name ?>
                  </td>
</tr>
<tr>
                  <td class="key" width="10%">Cần thanh toán: </td>
                  <td width="90%">
                  <input type="text" size="130" name="canthanhtoan" style="width: 100%;" value="<?= isset($dan) ? $obj->canthanhtoan : '' ?>"/>
                  </td>
                </tr>
       <tr>
                  <td class="key" width="10%">Đã thanh toán: </td>
                  <td width="90%">
                  <input type="text" size="130" name="dathanhtoan" style="width: 100%;" value="<?= isset($dan) ? $obj->dathanhtoan : '' ?>"/>
                  </td>
                </tr>
                <tr>
                        <td class="key" width="10%">Gộp</td>
                        <td>
                            <input type="radio" name="gop" value="1"<?= isset($dan) ? $obj->gop==1?' checked':'' : '' ?>>&nbsp;Yes
                            <input type="radio" name="gop" value="0"<?= isset($dan) ? $obj->gop==0?' checked':'' : '' ?>>&nbsp;No
                        </td>
</tr>
<?= isset($dan) ? '<tr><td class="key" width="10%">Thời gian</td><td width="90%">'.date("h:i:s d/m/Y",$obj->time).'</td></tr>' : '' ?>

<tr>
                        <td class="key" width="10%">Trạng thái</td>
                        <td>
                            <input type="radio" name="status" value="0"<?= isset($dan) ? $obj->status==0?' checked':'' : '' ?>>&nbsp;Không hoạt động
                            &emsp;
                            <input type="radio" name="status" value="1"<?= isset($dan) ? $obj->status==1?' checked':'' : '' ?>>&nbsp;Chờ xử lý
                            &emsp;
                            <input type="radio" name="status" value="2"<?= isset($dan) ? $obj->status==2?' checked':'' : '' ?>>&nbsp;Đang xử lý...
                            &emsp;
                            <input type="radio" name="status" value="3"<?= isset($dan) ? $obj->status==3?' checked':'' : '' ?>>&nbsp;Lỗi!
                            &emsp;
                            <input type="radio" name="status" value="4"<?= isset($dan) ? $obj->status==4?' checked':'' : '' ?>>&nbsp;Bị khoá!
                            &emsp;
                            <input type="radio" name="status" value="5"<?= isset($dan) ? $obj->status==5?' checked':'' : '' ?>>&nbsp;Đã xoá!
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