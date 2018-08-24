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
                  <td class="key" width="10%">Số xu</td>
                  <td width="90%">
                  <input type="text" size="130" name="balance" style="width: 100%;" value="<?= isset($dan) ? $obj->balance : '' ?>"/>
                  </td>
                </tr>
               <tr>
                  <td class="key" width="10%">Cấp độ TK</td>
                  <td width="90%">
                  	<label>
                          <input type="radio" name="group_id" value="1" <?= (isset($dan) && $obj->group_id == 1) ? 'checked' : '' ?>  />
                            Admin    
                      </label>
                      <label>
                          <input type="radio" name="group_id" value="2" <?= (isset($dan) && $obj->group_id == 2) ? 'checked' : '' ?>  />
                             Đại lý cấp 1
                      </label>
                      <label>
                          <input type="radio" name="group_id" value="3" <?= (isset($dan) && $obj->group_id == 3) ? 'checked' : '' ?> />
                             Đại lý cấp 2
                      </label>
                  </td>
                </tr>
                <?php include('../../includes/status.php');  ?>   
                </table>
                </div>
                <button type="submit" class="btn btn-default" name="ok">Lưu</button>
				<input type="hidden" name="controller" value="<?= $entity ?>">
				<input type="hidden" name="id" value="<?= isset($dan) ? $obj->getId() : '' ?>">
				<input type="hidden" name="action" value="updateuser">
                </form>
</div>
<?php include('../../includes/cn-footer.php'); ?>